<?

namespace Longman\TelegramBot\Commands\AdminCommands;

use Longman\TelegramBot\DB;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Command;
use Longman\TelegramBot;

class StatsCommand extends Command
{

    protected $name = 'stats';
    protected $description = 'Statistics by day, week, month';
    protected $usage = '/';
    protected $version = '1.0.0';
    protected $enabled = true;
    protected $public = true;


    public function execute() {
        $update = $this->getUpdate();
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $this->sendStaticticsByPeriod(7, $chat_id); //Statistics by one day.
    }


    private function sendStaticticsByPeriod($lastDays, $chat_id) {
        $msg = 'Usage statistics for the period '.date("Y-m-d",strtotime("-".$lastDays.'days')) .' - ' .date("Y-m-d") ."\n\n";

        $msg .= "Command usage statistics:\n";
        foreach (DB::getCountIncomeCommands($lastDays) as $item) {
            $msg .= $item['command'] .' - ' .$item['total'] .' usage.'."\n";
        }
        $msg .= "\nTotal message in the period ".DB::getCountIncomeMessageAll($lastDays) .' messages.';
        $msg .= "\n\nMost actively users:\n";
        foreach (DB::getCountUserActivity($lastDays) as $item) {
            $msg .= $item['first_name'] .' '.$item['last_name'].' ' .(($item['username']!='NULL') ? ('@'.$item['username']) : '') .' - ' .$item['message_count'] .' messages.' ."\n";
        }
        $data = array(
            'chat_id' => $chat_id,
            'text' => $msg,
        );
        Request::sendMessage($data);
        return;
    }

}
