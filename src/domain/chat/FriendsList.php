<?php
namespace app\domain\chat;

class FriendsList
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @var array
     */
    protected $onlineUsers = [];

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Sets who is online among this friends list.
     * @param array $onlineUsers Online Users IDs.
     */
    public function setOnline(array $onlineUsers = [])
    {
        $this->onlineUsers = $onlineUsers;

        foreach ($this->data as $projectIndex => $projects) {
            foreach ($projects['threads'] as $threadIndex => $thread) {
                if (in_array($thread['other_party']['user_id'], $this->onlineUsers)) {
                    $this->data[$projectIndex]['threads'][$threadIndex]['online'] = true;
                }
            }
        }
    }

    /**
     * @return boolean
     */
    public function isEmpty()
    {
        return empty($this->data);
    }

    /**
     * Ids of users in the friends list, if given a prefix return a two-dimensional array with ids and keys
     * @param string Prefix to return the key of users in the friends list
     * @return array
     */
    /*
    * TIP FOR SPEED (when it really matters):
    * The nested loops are slow, it's better implement the code into one, especially with a lot of data.
    */
    public function getUserIds($prefixKey = "")
    {
        $userIds = [];
        //--to avoid walking the entire $userIds to get user keys--//
        $userKeysIds = [];

        foreach ($this->data as $project) {
            foreach ($project['threads'] as $thread) {
                array_push($userIds, $thread['other_party']['user_id']);
                //--I don't know what is more fast (above or below)--//
                $userKeysIds[] = $prefixKey . $thread['other_party']['user_id'];
            }
        }

        if(empty($prefixKey))
            return array_unique($userIds);
        else
            return ["ids" => array_unique($userIds), "keys" => array_unique($userKeysIds)];
    }

    /**
     * Return JSON representation.
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->data);
    }

    /**
     * Return array representation.
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }
}
?>