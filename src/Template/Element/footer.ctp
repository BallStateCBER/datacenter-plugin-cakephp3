<footer id="footer">
    <div class="max_width">
        <div id="cberlogo_copyright">
            <a href="http://www.bsu.edu/cber">
                <img src="/data_center/img/BallStateCBER-red.png" alt="Ball State Logo"/>
                <span class="sr-only">
                    Ball State University: Center for Business and Economic Research
                </span>
            </a>
            <p>
                &copy; Center for Business and Economic Research, Ball State University
            </p>
        </div>
        <section>
            <section>
                <?php if ($this->fetch('footer_about')): ?>
                    <?= $this->fetch('footer_about') ?>
                <?php else: ?>
                    <h2>About Ball State CBER Data Center</h2>
                    <p>
                        Ball State CBER Data Center is one-stop shop for economic data including demographics, education, health, and social
                        capital. Our easy-to-use, visual web tools offer data collection and analysis for grant writers, economic developers, policy
                        makers, and the general public.
                    </p>
                    <p>
                        Ball State CBER Data Center (<a href="http://cberdata.org">cberdata.org</a>) is a product of the Center for Business and Economic Research at Ball State
                        University. CBER's mission is to conduct relevant and timely public policy research on a wide range of economic issues
                        affecting the state and nation. <a href="http://www.bsu.edu/cber">Learn more about CBER.</a>
                    </p>
                <?php endif; ?>
                <p>
                    <a href="http://cberdata.org/terms">Terms of Service</a>
                </p>
            </section>
            <section>
                <h2>Center for Business and Economic Research</h2>
                <address>
                    Ball State University &bull; Whitinger Business Building, room 149<br />
                    2000 W. University Ave.<br />
                    Muncie, IN 47306-0360
                </address>
                <dl>
                    <dt>Phone:</dt>
                    <dd>765-285-5926</dd>

                    <dt>Email:</dt>
                    <dd><a href="mailto:cber@bsu.edu">cber@bsu.edu</a></dd>

                    <dt>Website:</dt>
                    <dd><a href="http://www.bsu.edu/cber">www.bsu.edu/cber</a></dd>

                    <dt>Facebook:</dt>
                    <dd><a href="http://www.facebook.com/BallStateCBER">www.facebook.com/BallStateCBER</a></dd>

                    <dt>Twitter:</dt>
                    <dd><a href="http://www.twitter.com/BallStateCBER">www.twitter.com/BallStateCBER</a></dd>
                </dl>
            </section>
        </section>
    </div>
</footer>
