<?php
    $poll_id = nf_get_post_option('poll_id');

    if ( $view->flag_vote && $poll_id ) {
        global $post, $current_user;
        $closed = nf_get_post_option('closed');
    ?>
    <table style="font-size:14px; color:#666666;" bgcolor="#f5f5f5" width="100%" cellspacing="0" cellpadding="0" border="0" <?php post_class() ?>>
		<tr>
			<td style="height:20px;"></td>
		</tr>
		<tr>
			<td>
				<table  width="600" cellspacing="0" align="center" cellpadding="0" border="0">
                    <?php
                    if ( $closed ) { ?>
                        <tr>
                            <td class="poll_closed" style="color:#890000; padding:10px 0 20px 0; font-size:16px; font-family:Arial;" colspan="4">Голосование закрыто!</td>
                        </tr>
                        <?php
                    }
                    ?>

                    <tr class="entry">
                        <td>
                            <?php
                            //excerpt_limit(20);
                            the_content(__('Read the rest of this entry &raquo;', 'nix'));
                            ?>
                        </td>
                    </tr>
                    <?php
                        $answers = nf_get_post_option('answers', false, $poll_id);
                        $type = nf_get_post_option('type', false, $poll_id);
                    ?>
                    <tr id="poll-videos">

                       <?php
                        if (is_array($answers) && $type !== 'radio') {
                            shuffle($answers);
                        }
                        if (is_array($answers)) {
                            $count = 0;
                            foreach ($answers as $answer_num => $answer) {
                                ?>
                                <td >
                                    <table style="padding:0 0 20px;">
                                        <tr>
                                            <td style="width:150px;">
                                                <h3 style="font-size:16px; font-family:Arial;"><?php echo $answer['title']; ?></h3>
                                                <?php
                                                if ($answer['video'] && $answer['video']['source'] == 'youtube' ) {
                                                    $youtube_img_url = 'http://img.youtube.com/vi/' . $answer['video']['code'] .'/0.jpg';
                                                    $dst_path = 'youtube/' . $answer['video']['code'] .'.jpg';
                                                    $img_url = thumb_import( $youtube_img_url, $dst_path, '130', '130', true );
                                                    ?>
                                                    <a href="<?php echo 'http://www.youtube.com/watch?v=' . $answer['video']['code'];  ?>"><span style="padding:5px; background:#ffffff; float:left;"><img  src="<?php echo $img_url; ?>" /></span></a>
                                                    <?php //nf_get_video($answer['video'], 452, 282); ?>
                                                <?php }
                                                ?>
                                                <?php if ($answer['picture']) { ?>
                                                    <table style=" background:#ffffff; " cellspacing="0" cellpadding="0" border="0">
                                                        <tr>
                                                            <td style="padding:5px;"><img style="float:left;" title="" alt="" src="<?php echo thumb($answer['picture']['path'], '130', '130', true); ?>"/></td>
                                                        </tr>
                                                    </table>
                                                    <br/>
                                                <?php } ?>
                                                <?php echo wpautop($answer['desc']); ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            <?php
                                $count++;
                                if ( $count == 4 ) { ?>
                                </tr><tr>
                                <?php
                                    $count = 0;
                                }
                            }
                        }
                        ?>
                            </tr>
					</table>
					<table width="600" cellspacing="0" align="center" cellpadding="0" border="0" style="padding:0 0 20px; color:#666666; font-size:14px;">
					<?php if ( !$closed ) { ?>
					<tr>
						<td width="100%">
							<a href="<?php the_permalink(); ?>"><img alt="Голосовать" src="<?php echo THEME_DIR_URI . '/images/golosovat.jpg' ?>" style="margin:10px 0 0 20px; overflow:hidden;" alt=""/></a>
						</td>
					</tr>
					<?php } ?>
                    <?php if (nf_get_post_option( 'closing_vote', '', $poll_id )) { ?>
                    <?php
                        $months = explode("|", '|января|февраля|марта|апреля|мая|июня|июля|августа|сентября|октября|ноября|декабря');
                        $format = preg_replace("/%B/", $months[date( 'n', strtotime( nf_get_post_option( 'closing_vote', false, $poll_id ) . nf_get_post_option( 'time_closing', false, $poll_id ) ))], '%B');

                        $date_closing = strftime( '%d %G %H:%M', strtotime( nf_get_post_option( 'closing_vote', false, $poll_id ) . nf_get_post_option( 'time_closing', false, $poll_id ) ) );
                        $month_closing = strftime( $format, strtotime( nf_get_post_option( 'closing_vote', false, $poll_id ) . nf_get_post_option( 'time_closing', false, $poll_id ) ) );
                    ?>
					<tr>
						<td style="width:30%;">
                            Голосование будет закрыто
                            <span class="number"><?php echo $date_closing[0]; ?></span><span class="number"><?php echo $date_closing[1]; ?></span><span class="txt"><?php echo $month_closing; ?></span>
                            <span class="number"><?php echo $date_closing[3]; ?></span><span class="number"><?php echo $date_closing[4]; ?></span><span class="number"><?php echo $date_closing[5]; ?></span><span class="number"><?php echo $date_closing[6]; ?></span>
                            <span class="txt letter">в </span>
                            <span class="number"><?php echo $date_closing[8]; ?></span><span class="number"><?php echo $date_closing[9]; ?></span><span class="letter"><?php echo $date_closing[10]; ?></span><span class="number"><?php echo $date_closing[11]; ?></span><span class="number"><?php echo $date_closing[12]; ?></span>
						</td>
					</tr>
                    <?php } ?>
				</table>		
			</td>
		</tr>
		<tr>
			<td style="height:20px;"></td>
		</tr>
    </table>
<?php }