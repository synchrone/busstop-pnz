<?php defined('SYSPATH') or die('No direct script access.');
/**
 *
 */
class Task_Watch extends Minion_Task
{
    const START = 0;
    const UPDATE = 1;
    const FINISH = 2;

    protected $_options = array(
        'collectors' => 'counter,mapper'
    );

    protected function sleep($seconds, Closure $callback){
        if(sleep($seconds) > 0){
            $callback(SIGINT);
        }
    }

    protected $_collectors = array();

    protected function _execute(array $options)
    {
        $quit = false;
        $int_handler = function($h)use(&$quit){ $quit = true;};
        pcntl_signal(SIGINT,$int_handler);

        $this->setup_collectors();

        while(true && !$quit){
            Minion_CLI::write_replace('Fetching vehicles...');

            $vehicles = Model_Remote::vehicles();

            $time = new DateTime();
            Minion_CLI::write_replace(
                sprintf('Got vehicles on %s!', $time->format(DateTime::ATOM))
                ,true
            );

            $this->update_collectors($vehicles);

            Minion_CLI::write_replace('Waiting 30 sec...');
            $this->sleep(30, $int_handler);
        }

        $this->update_collectors($n=array(),self::FINISH);

        Minion_CLI::write();
    }

    protected function setup_collectors()
    {
        foreach(explode(',',$this->_options['collectors']) as $collector)
        {
            $this->_collectors[] = array($this,$collector);
            $this->$collector(self::START, array());
        }
    }
    protected function update_collectors($vehicles, $state = self::UPDATE)
    {
        foreach($this->_collectors as $collector)
        {
            call_user_func($collector,$state,$vehicles);
        }
    }

    protected function csv_writer($state, $filename, array $data)
    {
        static $fh;

        switch($state)
        {
            case self::START:
                $fh[$filename] = fopen($filename,'w+');
                if(!empty($data)){
                    fputcsv($fh[$filename],$data);
                }
                break;
            case self::UPDATE:
                fputcsv($fh[$filename], $data);
                break;
            case self::FINISH:
                fclose($fh[$filename]);
                break;
        }
    }

    /**
     * @param $state int
     * @param $vehicles Model_Vehicle[]
     */
    protected function counter($state, $vehicles)
    {
        $filename = 'counter.csv';
        if($state != self::UPDATE)
        {
            $this->csv_writer($state, $filename, array('time','rtype','count'));
            return;
        }

        $vcount = array();
        foreach($vehicles as $vehicle)
        {
            if(!isset($vcount[$vehicle->rtype])){
                $vcount[$vehicle->rtype] = 0;
            }
            $vcount[$vehicle->rtype]++;
        }
        foreach($vcount as $rtype=>$count)
        {
            $this->csv_writer($state, $filename, array(time(), $rtype, $count));
        }
    }

    /**
     * @param $state int
     * @param $vehicles Model_Vehicle[]
     */
    protected function mapper($state, $vehicles)
    {
        static $last_seen;
        if($last_seen === null)
        {
            $last_seen = array();
        }

        $filename = 'mapper.csv';
        if($state != self::UPDATE)
        {
            $this->csv_writer($state, $filename, array('rtype','lat','lon','lasttime'));
            return;
        }

        foreach($vehicles as $vehicle)
        {
            if(Arr::get($last_seen,$vehicle->id) >= $vehicle->lasttime()->getTimestamp())
            { //same record, not stored in csv
                continue;
            }

            $this->csv_writer($state, $filename,
                array($vehicle->rtype, $vehicle->lat(), $vehicle->lon(), $vehicle->lasttime)
            );

            $last_seen[$vehicle->id] = $vehicle->lasttime()->getTimestamp();
        }

    }
}