<?php
    if ( $flag_vote && $poll_id ) {
        global $post, $current_user;
        ?>

        <div <?php post_class() ?>>
            <?php
                if ( $closed_vote == 'logged_out' ){ ?>
                    <div class="entry">
                        <div class="message warning">
                            <?php NFM_Login::please_login('Перед тем, как голосовать'); ?>
                        </div>
                    </div>
                    <?php
                }
                else if ( $closed_vote == 'closed' ) {
                    ?>
                    <div class="message success">Голосование закрыто</div>
                    <?php
                }

                if ( isset($_REQUEST['pull-answers']) ) {
                    NFM_Poll::vote($poll_id, $_REQUEST['pull-answers']);
                }
                $current_user_answers = NFM_Poll::get_current_user_answers($poll_id);
            ?>

            <form id="poll-form" action="" method="POST">
                <?php
                    $answers = nf_get_post_option( 'answers', false, $poll_id );
                    $type    = nf_get_post_option( 'type', false, $poll_id );
                    $random  = nf_get_post_option( 'random', false, $poll_id );

                    if ( is_array($answers) ) {
                        if ( count($answers) == 2 ) {
                            $count_class = 'two_answers';
                        }
                        else if ( count($answers) == 3 ) {
                            $count_class = 'three_answers';
                        }
                        else {
                            $count_class = 'more_answers';
                        }
                    }
                ?>
                <ol id="poll-videos" class="<?php echo $count_class; ?>">
                    <?php
                    if (is_array($answers) && $random) {
                        shuffle($answers);
                    }
                    if (is_array($answers)) {
                        $count = 0;
                        foreach ($answers as $answer_num => $answer) {
                            ?>
                            <li>
                                <h3><?php echo $answer['title']; ?></h3>
                                <?php
                                    if ($answer['video'] && $answer['video']['source'] == 'youtube' ) {
                                        $youtube_img_url = 'http://img.youtube.com/vi/' . $answer['video']['code'] .'/0.jpg';
                                        $dst_path = 'youtube/' . $answer['video']['code'] .'.jpg';
                                        $img_url = thumb_import( $youtube_img_url, $dst_path, '208', '208', true );
                                    ?>
                                    <a class="video" href="<?php echo 'http://www.youtube.com/watch?v=' . $answer['video']['code'] . '?fs=1&amp;autoplay=1';  ?>"><img src="<?php echo $img_url; ?>" /></a>
                                <?php }
                                ?>
                                <div>
                                    <label>
                                        <?php if ($answer['picture']) { ?>
                                            <a rel="vote-picture" href="<?php echo thumb($answer['picture']['path'], '1280', '1024'); ?>"><img title="" alt="" src="<?php echo thumb($answer['picture']['path'], '208', '208', true); ?>"/></a>
                                            <br/>
                                        <?php } ?>
                                        <input
                                            <?php if ( $closed_vote ) echo 'disabled title="Голосование закрыто"' ?>
                                            <?php if ( ! is_user_logged_in() ) echo 'disabled title="Пожалуйста залогинтесь"' ?>
                                            <?php if (in_array($answer['id'], $current_user_answers)) echo 'checked="1"'; ?>

                                            <?php if ($type === 'radio') { ?>
                                                type="radio"
                                            <?php } else { ?>
                                                type="checkbox"
                                            <?php } ?>

                                            value="<?php echo $answer['id'] ?>" name="pull-answers[]"/>
                                        <?php echo strip_tags($answer['title']); ?>
                                    </label>
                                    <?php echo wpautop($answer['desc']); ?>
                                </div>
                            </li>
                        <?php
                            $count++;
                            if ( $count == 4) {
                                    $count = 0;
                        ?>
                                </ol><ol>
                            <?php }
                        }
                    }
                    ?>
                </ol>
                <input
                    type="submit"
                    <?php if ( $closed_vote ) echo 'disabled title="Голосование закрыто" class="disabled"' ?>
                    <?php if ( ! is_user_logged_in() ) echo 'disabled title="Пожалуйста залогинтесь"' ?>
                    value="Голосовать">
					
                <?php if (( !$closed_vote ) && ( nf_get_post_option( 'closing_vote', '', $poll_id ))) { ?>
                    <div class="date_close">
                        <?php
                            $residual_time = $closing_vote - time();
                            $day = (int)( $residual_time / 86400 );
                            $hour = ( $residual_time / 3600 ) % 24;
                            $min = ( $residual_time / 60 ) % 60;

                            $timer_format = sprintf( '%02d%02d%02d', $day, $hour, $min );
                        ?>
                        Осталось:
                        <?php if ($day) { ?>
                            <span class="number"><?php echo $timer_format[0]; ?></span><span class="number"><?php echo $timer_format[1]; ?></span><span class="txt"><?php echo getNumEnding($timer_format[0] . $timer_format[1], array('день', 'дня', 'дней')); ?></span>
                        <?php } ?>
                        <?php if ($hour) { ?>
                            <span class="number"><?php echo $timer_format[2]; ?></span><span class="number"><?php echo $timer_format[3]; ?></span><span class="txt"><?php echo getNumEnding($timer_format[2] . $timer_format[3], array('час', 'часа', 'часов')); ?></span>
                        <?php } ?>
                            <span class="number"><?php echo $timer_format[4]; ?></span><span class="number"><?php echo $timer_format[5]; ?></span><span class="txt"><?php echo getNumEnding($timer_format[4] . $timer_format[5], array('минута', 'минуты', 'минут')); ?></span>
                    </div>
                <?php } ?>
            </form>
        </div>
    <?php } 