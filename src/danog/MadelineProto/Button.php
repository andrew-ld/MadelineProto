<?php
/*
Copyright 2016-2017 Daniil Gentili
(https://daniil.it)
This file is part of MadelineProto.
MadelineProto is free software: you can redistribute it and/or modify it under the terms of the GNU Affero General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
MadelineProto is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
See the GNU Affero General Public License for more details.
You should have received a copy of the GNU General Public License along with MadelineProto.
If not, see <http://www.gnu.org/licenses/>.
*/

namespace danog\MadelineProto;

class Button extends \Volatile implements \JsonSerializable
{
    use \danog\Serializable;
    private $info = [];

    public function __construct($API, $message, $button)
    {
        foreach ($button as $key => $value) {
            $this->{$key} = $value;
        }
        $this->info['peer'] = $message['to_id'];
        $this->info['id'] = $message['id'];
        $this->info['API'] = $API;
    }

    public function click($donotwait = false)
    {
        switch ($this->_) {
            default: return false;
            case 'keyboardButtonUrl': return $this->url;
            case 'keyboardButton': return $this->info['API']->method_call('messages.sendMessage', ['peer' => $this->info['peer'], 'message' => $this->text, 'reply_to_msg_id' => $this->info['id']], ['datacenter' => $this->info['API']->datacenter->curdc]);
            case 'keyboardButtonCallback': return $this->info['API']->method_call('messages.getBotCallbackAnswer', ['peer' => $this->info['peer'], 'msg_id' => $this->info['id'], 'data' => $this->data], ['noResponse' => $donotwait, 'datacenter' => $this->info['API']->datacenter->curdc]);
            case 'keyboardButtonGame': return $this->info['API']->method_call('messages.getBotCallbackAnswer', ['peer' => $this->info['peer'], 'msg_id' => $this->info['id'], 'game' => true], ['noResponse' => $donotwait, 'datacenter' => $this->info['API']->datacenter->curdc]);
        }
    }

    public function jsonSerialize()
    {
        $res = get_object_vars($this);
        unset($res['info']);

        return $res;
    }
}