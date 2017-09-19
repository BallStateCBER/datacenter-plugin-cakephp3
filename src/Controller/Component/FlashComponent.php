<?php
namespace DataCenter\Controller\Component;

use Cake\Controller\Component;
use Cake\Event\Event;

class FlashComponent extends Component
{
	public $messages;

    /**
     * BeforeRender method
     *
     * @param \Cake\Event\Event $event A CakePHP event
     * @return void
     */
    public function beforeRender(Event $event)
	{
		$this->prepareFlashMessages($event);
        $this->_registry->getController()->set('flashMessages', $this->messages);
	}

    /**
     * Adds a string message to the session with a class of 'success', 'error', or 'notification' (default)
     * OR adds a variable to dump and the class 'dump'
     *
     * @param string $message The message
     * @param string $class The class of the message
     * @return void
     */
	public function set($message, $class = 'notification')
	{
		// Dot notation doesn't seem to allow for the equivalent of $messages['error'][] = $message
		$storedMessages = $this->request->session()->read('FlashMessage');
		$storedMessages[] = compact('message', 'class');
		$this->request->session()->write('FlashMessage', $storedMessages);
	}

    /**
     * A convenience method for set($message, 'success')
     *
     * @param string $message The message
     * @return void
     */
	public function success($message)
	{
		$this->set($message, 'success');
	}

    /**
     * A convenience method for set($message, 'error')
     *
     * @param string $message The message
     * @return void
     */
	public function error($message)
	{
		$this->set($message, 'error');
	}

    /**
     * A convenience method for set($message, 'notification')
     *
     * @param string $message The message
     * @return void
     */
	public function notification($message)
	{
		$this->set($message, 'notification');
	}

    /**
     * A convenience method for set($message, 'dump')
     *
     * @param string $message The message
     * @return void
     */
	public function dump($variable)
	{
		$this->set($variable, 'dump');
	}

	/**
     * Sets an array to be displayed by the element 'flash_messages'
     *
     * @param Event $event A CakePHP event
     * @return void
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
