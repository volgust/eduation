<?php
/**
 * @package WordPress
 * @subpackage Nix Theme Framework
 *
 * global $wp_query;
 * $wp_query = new WP_Query('post_type=poll&posts_per_page=6&meta_key=' . NF_CF_OPTIONS_PREF . 'future_zone&meta_value=1');
 *
 * http://wiki.kotelnitskiy.php.nixsolutions.com/blog/2010/05/06/custom-content-type-2/
 */
require_once THEME_DIR . '/core/libs/postType.php';

class NF_Poll_Post_Type extends postType {
    function __construct() {

        $this->args['label'] = __('Голосования', 'nix');
        $this->args['singular_name'] = __('Голосование', 'nix');

        $this->args['supports'] = array(
            'title',
        );

        parent::__construct('poll', $this->args);
        //$this->addEditColumn('comments', 'Comments');

        $_main_box = array(
            array(
                'name' => 'type',
                'type' => 'select',
                'title' => 'Тип опроса',
                'desc' => 'Один или Множество ответов',
                'options' => array(
                    'checkbox' => 'Множество ответов',
                    'radio' => 'Один из многих')
            ),
            array(
                'name' => 'random',
                'type' => 'checkbox',
                'title' => __('Случайный порядок', 'nix'),
                'label' => __('Включить случайный порядок вариантов ответов', 'nix')
            ),
            array(
                'name' => 'closing_vote',
                'type' => 'date',
                'title' => __('Closing date of the vote', 'nix'),
            ),
            array(
                'name' => 'time_closing',
                'type' => 'textinput',
                'title' => __('Время закрытия голосования', 'nix'),
                'def' => '19:00'
            ),
            array(
                'name' => 'closed',
                'type' => 'checkbox',
                'title' => __('Голосование закрыто', 'nix'),
                'label' => __('Голосование окончено или закрыто', 'nix')
            ),
            array(
                'name' => 'h3',
                'type' => 'header',
                'title' => __('Варианты ответов', 'nix')
            ),
            array(
                'name' => 'answers',
                'type' => 'table',
                'title' => 'Варианты ответов',
                'h4' => __('Варианты ответов', 'nix'),
                'fields' => array(
                    array(
                        'name' => 'title',
                        'type' => 'textinput',
                        'title' => 'Название',
                        'desc' => ''
                    ),
                    array(
                        'name' => 'id',
                        'type' => 'textinput',
                        'title' => 'Идентификатор',
                        'desc' => ''
                    ),
                    array(
                        'name' => 'video',
                        'type' => 'video',
                        'title' => 'Видео',
                        'desc' => ''
                    ),
                    array(
                        'name' => 'picture',
                        'type' => 'image',
                        'dir' => 'polls',
                        'title' => 'Картинка',
                        'desc' => ''
                    ),
                    array(
                        'name' => 'desc',
                        'type' => 'textarea',
                        'title' => 'Описание',
                        'desc' => ''
                    ),
                    array(
                        'name' => 'order',
                        'type' => 'tableorder',
                        'title' => __('Порядок', 'nix'),
                        'desc' => ''
                    )
                )
            ),
            array(
                'name' => 'results_block',
                'type' => 'result_vote',
                'title' => __('Election results', 'nix')
            ),
        );

        $this->addMetaBox('poll_main', __('Options', 'nix'), $_main_box);
        //$this->addEditColumn('poll_image', __('Image', 'nix'));
    }

    public function customColumn_poll_image() { ?>
        <img src="<?php echo thumb(nf_get_post_option('image', 'path'), 70, 64); ?>" alt="" title=""/>
    <?php
    }

    static public function result_vote($poll_id) {
        global $db_entity_polls;

        $votes_arr = $db_entity_polls->get_results_vote( $poll_id );

        $answers = nf_get_post_option('answers', false, $poll_id);
        foreach ($votes_arr as $vote_i => $vote) {
            $_title = '';
            foreach ($answers as $answer) {
                if ($answer['id'] == $vote['answer']) {
                    $_title = $answer['title'];
                    break;
                }
            }
            $votes_arr[$vote_i]['title'] = $_title;
        }

        return $votes_arr;
    }
}

new NF_Poll_Post_Type();