<?php
require_once dirname(__FILE__).'/poll.content-type.php';
require_once dirname(__FILE__).'/poll.db-entity.php';

class NFM_Poll {

    /**
     * Function setup variable
     *
     * @param $view
     * @param $poll_id
     * @param $flag_vote
     *
     */
    static public function setup_vote_view($view, $poll_id, $flag_vote) {
        $view->flag_vote = $flag_vote;
        $view->poll_id = $poll_id;

        $timezone = get_option('timezone_string');
        date_default_timezone_set($timezone);
        $closing_vote = strtotime( nf_get_post_option( 'closing_vote', false, $view->poll_id ) . nf_get_post_option( 'time_closing', false, $view->poll_id ) );
        $view->closing_vote = $closing_vote;

        $closed_vote = nf_get_post_option('closed', false, $poll_id);

        if ( !is_user_logged_in() ) {
            $closed_vote = 'logged_out';
        }
        else if ( ($view->closing_vote && $view->closing_vote <= time()) ||  $closed_vote ) {
            $closed_vote = 'closed';
        }
        $view->closed_vote = $closed_vote;
    }
    
    static public function vote($poll_id, $answers) {
        global $db_entity_polls;

        if ( is_user_logged_in() && is_array($answers) && count($answers)) {

            $current_user = wp_get_current_user();

            $db_entity_polls->delete_entries(
                array(
                    'user_id' => $current_user->ID,
                    'poll_id' => $poll_id,
                )
            );

            foreach ($answers as $answer) {
                $db_entity_polls->add_entries(array(
                    'user_id' => $current_user->ID,
                    'poll_id' => $poll_id,
                    'answer_id' => $answer,
                ));
            }

            //echo $current_user->ID;
            //print_r($answers);
            ?>
            <div class="message success">Ваш голос учтён. Спасибо!</div>
        <?php
        }
    }

    static public function get_current_user_answers($poll_id) {
        global $db_entity_polls;

        if ( is_user_logged_in() ) {

            $current_user = wp_get_current_user();

            $pull_votes = $db_entity_polls->get_entries(
                array(
                    'user_id' => $current_user->ID,
                    'poll_id' => $poll_id,
                ),
                $orders = array(
                    'poll_id' => 'ASC'
                )
            );

            $current_user_answers = array();
            foreach ($pull_votes as $_vote) {
                $current_user_answers[] = $_vote['answer_id'];
            }

            return $current_user_answers;
        }
        else return array();
    }
}