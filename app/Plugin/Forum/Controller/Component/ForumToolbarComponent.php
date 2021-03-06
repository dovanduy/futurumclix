<?php
/**
 * Copyright (c) 2018 FuturumClix
 *
 * This program is free software: you can redistribute it and/or  modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Please notice this program incorporates variety of libraries or other
 * programs that may or may not have their own licenses, also they may or
 * may not be modified by FuturumClix. All modifications made by
 * FuturumClix are available under the terms of GNU Affero General Public
 * License, version 3, if original license allows that.
 *
 * @copyright   2006-2013, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/admin/blob/master/license.md
 * @link        http://milesj.me/code/cakephp/admin
 */

/**
 * @property Controller $Controller
 * @property SessionComponent $Session
 * @property AuthComponent $Auth
 */
class ForumToolbarComponent extends Component {

    /**
     * Components.
     *
     * @type array
     */
    public $components = array('Session', 'Auth');

    /**
     * Store the Controller.
     *
     * @param Controller $Controller
     * @return void
     */
    public function initialize(Controller $Controller) {
        $this->Controller = $Controller;
    }

    /**
     * Initialize the session.
     *
     * @param Controller $Controller
     * @return void
     */
    public function startup(Controller $Controller) {
        $this->Controller = $Controller;

        $user_id = $this->Auth->user('id');

        if (!$this->Session->check('Forum')) {
            if ($user_id && $this->Auth->user(Configure::read('User.fieldMap.status')) != Configure::read('User.statusMap.banned')) {
                $this->Session->write('Forum.moderates', ClassRegistry::init('Forum.Moderator')->getModerations($user_id));
            }

            $this->Session->write('Forum.lastVisit', date('Y-m-d H:i:s'));
        }
    }

    /**
     * Calculates the page to redirect to.
     *
     * @param int $topic_id
     * @param int $post_id
     * @param bool $return
     * @return mixed
     */
    public function goToPage($topic_id = null, $post_id = null, $return = false) {
        $topic = ClassRegistry::init('Forum.Topic')->getById($topic_id);
        $slug = !empty($topic['Topic']['slug']) ? $topic['Topic']['slug'] : null;

        // Certain page
        if ($topic_id && $post_id) {
            $posts = ClassRegistry::init('Forum.Post')->getIdsForTopic($topic_id);
            $perPage = Configure::read('Forum.settings.postsPerPage');
            $totalPosts = count($posts);

            if ($totalPosts > $perPage) {
                $totalPages = ceil($totalPosts / $perPage);
            } else {
                $totalPages = 1;
            }

            if ($totalPages <= 1) {
                $url = array('plugin' => 'forum', 'controller' => 'topics', 'action' => 'view', $slug, '#' => 'post-' . $post_id);
            } else {
                $posts = array_values($posts);
                $flips = array_flip($posts);
                $position = $flips[$post_id] + 1;
                $goTo = ceil($position / $perPage);
                $url = array('plugin' => 'forum', 'controller' => 'topics', 'action' => 'view', $slug, 'page' => $goTo, '#' => 'post-' . $post_id);
            }

        // First post
        } else if ($topic_id && !$post_id) {
            $url = array('plugin' => 'forum', 'controller' => 'topics', 'action' => 'view', $slug);

        // None
        } else {
            $url = $this->Controller->referer();

            if (!$url || strpos($url, 'delete') !== false) {
                $url = array('plugin' => 'forum', 'controller' => 'forum', 'action' => 'index');
            }
        }

        if ($return) {
            return $url;
        }

        $this->Controller->redirect($url);
        return true;
    }

    /**
     * Simply marks a topic as read.
     *
     * @param int $topic_id
     * @return void
     */
    public function markAsRead($topic_id) {
        $readTopics = (array) $this->Session->read('Forum.readTopics');
        $readTopics[] = $topic_id;

        $this->Session->write('Forum.readTopics', array_unique($readTopics));
    }

    /**
     * Updates the session topics array.
     *
     * @param int $topic_id
     * @return void
     */
    public function updateTopics($topic_id) {
        $topics = $this->Session->read('Forum.topics');

        if ($topic_id) {
            if (is_array($topics)) {
                $topics[$topic_id] = time();
            } else {
                $topics = array($topic_id => time());
            }

            $this->Session->write('Forum.topics', $topics);
        }
    }

    /**
     * Updates the session posts array.
     *
     * @param int $post_id
     * @return void
     */
    public function updatePosts($post_id) {
        $posts = $this->Session->read('Forum.posts');

        if ($post_id) {
            if (is_array($posts)) {
                $posts[$post_id] = time();
            } else {
                $posts = array($post_id => time());
            }

            $this->Session->write('Forum.posts', $posts);
        }
    }

    /**
     * Do we have access to commit this action.
     *
     * @param array $validators
     * @return bool
     * @throws UnauthorizedException
     * @throws ForbiddenException
     */
    public function verifyAccess($validators = array()) {
        // SuperModerators have full control
        if ($this->Session->read('Acl.isSuper')) {
            return true;
        }

        // Do we have required role access?
        if (isset($validators['access'])) {
            if ($validators['access'] && (!$this->Session->check('Acl.roles') || !in_array($validators['access'], (array) array_keys($this->Session->read('Acl.roles'))))) {
                throw new UnauthorizedException();
            }
        }

        // Are we a moderator? Grant access
        if (isset($validators['moderate'])) {
            return in_array($validators['moderate'], $this->Session->read('Forum.moderates'));
        }

        // Is the item locked/unavailable?
        if (isset($validators['status'])) {
            if (!$validators['status']) {
                throw new ForbiddenException();
            }
        }

        // Does the user own this item?
        if (isset($validators['ownership'])) {
            if ($this->Auth->user('id') != $validators['ownership']) {
                throw new UnauthorizedException();
            }
        }

        return true;
    }

}
