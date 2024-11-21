<?php
declare(strict_types=1);

namespace app\command;

use app\service\BirthdayReminderService;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class BirthdayReminder extends Command
{
    protected function configure()
    {
        $this->setName('birthday:remind')
            ->setDescription('Send birthday reminders to users');
    }
    
    protected function execute(Input $input, Output $output)
    {
        $service = app()->make(BirthdayReminderService::class);
        $service->checkAndSendReminders();
        
        $output->writeln('Birthday reminders sent successfully!');
    }
} 