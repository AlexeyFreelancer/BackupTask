<?php

namespace BackupTask\Command\Notification;

abstract class NotificationAbstract
{

    protected $options;

    protected abstract function sendNotification($to, $subject, $message);

    public function __construct($options)
    {
        $this->options = $options;
    }

    public function run($backupStats)
    {
        $this->checkConfiguration();

        // get notification options
        $eventOptions = array();
        if ($backupStats['count_errors'] > 0 && !empty($this->options['on_failed'])) {
            $eventOptions = $this->options['on_failed'];
        } elseif (!empty($this->options['on_success'])) {
            $eventOptions = $this->options['on_success'];
        }

        // send notifications
        if ($eventOptions) {
            $message = $this->renderTemplate($eventOptions['template'], $backupStats);
            $this->sendNotification($eventOptions['to'], $eventOptions['subject'], $message);
        }
    }

    private function checkConfiguration()
    {
        $events = array('on_success', 'on_failed');

        // check if at list one event are exists
        $hasEvent = false;
        foreach ($events as $event) {
            if (!empty($this->options[$event])) {
                $hasEvent = true;
                break;
            }
        }
        if (!$hasEvent) {
            throw new \Exception('No events are found (' . implode(' / ', $events) . ')');
        }

        // event required options
        $eventOptions = array('to', 'subject', 'template');
        foreach ($events as $event) {
            foreach ($eventOptions as $eventOption) {
                if (empty($this->options[$event][$eventOption])) {
                    throw new \Exception("Required option '$eventOption' doesn't exists in event '$event' ");
                }
            }
        }
    }

    private function renderTemplate($template, $data)
    {
        extract($data);
        ob_start();
        include $template;
        return ob_get_clean();
    }

}