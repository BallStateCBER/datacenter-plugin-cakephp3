<?php
namespace DataCenter\Controller\Component;

use Cake\Controller\Component;

class FlashComponent extends Component
{
	public $messages;
    
    public function beforeRender(\Cake\Event\Event $event)
	{
		$this->prepareFlashMessages($event);
        $this->_registry->getController()->set('flashMessages', $this->messages);
	}

	// Adds a string message with a class of 'success', 'error', or 'notification' (default)
	// OR adds a variable to dump and the class 'dump'
	public function set($message, $class = 'notification')
	{
		// Dot notation doesn't seem to allow for the equivalent of $messages['error'][] = $message
		$storedMessages = $this->request->session()->read('FlashMessage');
		$storedMessages[] = compact('message', 'class');
		$this->request->session()->write('FlashMessage', $storedMessages);
	}

	public function success($message)
	{
		$this->set($message, 'success');
	}

	public function error($message)
	{
		$this->set($message, 'error');
	}

	public function notification($message)
	{
		$this->set($message, 'notification');
	}

	public function dump($variable)
	{
		$this->set($variable, 'dump');
	}

	/*
     * Sets an array to be displayed by the element 'flash_messages'
     * @param Event $event
     */
	private function prepareFlashMessages($event)
	{
		$storedMessages = $this->request->session()->read('FlashMessage');
		$this->request->session()->delete('FlashMessage');
        $authError = $this->request->session()->read('Message.auth');
		if ($authError) {
			$storedMessages[] = [
				'message' => $authError['message'],
				'class' => 'error'
			];
			$this->request->session()->delete('Message.auth');
		}
        $other_messages = $this->request->session()->read('Message.flash');
		if ($other_messages) {
			$storedMessages[] = [
				'message' => $other_messages['message'],
				'class' => 'notification'
			];
			$this->request->session()->delete('Message.flash');
		}
		if ($storedMessages) {
			foreach ($storedMessages as &$message) {
				if ($message['class'] == 'dump') {
					$message = [
						'message' => '<pre>'.print_r($message['message'], true).'</pre>',
						'class' => 'notification'
					];
				} else {
					$message['message'] = $message['message'];
				}
			}
		}
		$this->messages = $storedMessages;
	}
}