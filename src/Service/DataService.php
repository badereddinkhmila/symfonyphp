<?php

namespace App\Service;


use App\Repository\BloodPressureRepository;
use App\Repository\BloodSugarRepository;
use App\Repository\OxygenLevelRepository;
use App\Repository\TemperatureRepository;
use App\Repository\WeightRepository;

class DataService
{
    private $temp_repo;
    private $oxy_repo;
    private $weight_repo;
    private $bp_repo;
    private $gly_repo;
    public function __construct(TemperatureRepository $temp_repo,OxygenLevelRepository $oxy_repo,WeightRepository $weight_repo,BloodPressureRepository $bp_repo,BloodSugarRepository $gly_repo)
    {
        $this->temp_repo=$temp_repo;
        $this->oxy_repo=$oxy_repo;
        $this->weight_repo=$weight_repo;
        $this->bp_repo=$bp_repo;
        $this->gly_repo=$bp_repo;
    }

    public function getIotTemperature($gateway,$from,$to){
        $jsonResponse=array();
        $temp=array();
        $data=($this->temp_repo->findByBucket($gateway,$from,$to));
        $min_max=($this->temp_repo->findMaxMinByBucket($gateway,$from,$to));
        foreach($data as $dt){
            array_push($temp,[($dt['collect_time']->getTimestamp())*1000,$dt['temperature']]);
        }

        $jsonResponse['temperature']=$temp;
        $jsonResponse['max_min']=$min_max;
        return $jsonResponse;
    }
    public function getIotOxygen($gateway,$from,$to){
        $jsonResponse=array();
        $pulse=array();
        $spo2=array();
        $data=($this->oxy_repo->findByBucket($gateway,$from,$to));
        $min_max=($this->oxy_repo->findMaxMinByBucket($gateway,$from,$to));

        foreach($data as $dt){
            array_push($pulse,[($dt['collect_time']->getTimestamp())*1000,$dt['pulse']]);
            array_push($spo2,[($dt['collect_time']->getTimestamp())*1000,$dt['spo2']]);
        }
        $jsonResponse['pulse']=$pulse;
        $jsonResponse['spo2']=$spo2;
        $jsonResponse['max_min']=$min_max;
        return $jsonResponse;
    }
    public function getIotweight($gateway,$from,$to){
        $jsonResponse=array();
        $bmi=array();
        $bodyfat=array();
        $weight=array();
        $data=($this->weight_repo->findByBucket($gateway,$from,$to));
        $min_max=($this->weight_repo->findMaxMinByBucket($gateway,$from,$to));

        foreach($data as $dt){
            array_push($bmi,[($dt['collect_time']->getTimestamp())*1000,$dt['bmi']]);
            array_push($bodyfat,[($dt['collect_time']->getTimestamp())*1000,$dt['bodyfat']]);
            array_push($weight,[($dt['collect_time']->getTimestamp())*1000,$dt['weight']]);
        }
        $jsonResponse['bmi']=$bmi;
        $jsonResponse['bodyfat']=$bodyfat;
        $jsonResponse['weight']=$weight;
        $jsonResponse['max_min']=$min_max;
        return $jsonResponse;
    }

    public function getIotGlucose($gateway,$from,$to){
        $jsonResponse=array();
        $mg_dl=array();
        $mmol_l=array();

        $data=($this->gly_repo->findByBucket($gateway,$from,$to));
        $min_max=($this->gly_repo->findMaxMinByBucket($gateway,$from,$to));
        foreach($data as $dt){
            array_push($jsonResponse,[($dt['collect_time']->getTimestamp())*1000,$dt['mg_dl']]);
            array_push($jsonResponse,[($dt['collect_time']->getTimestamp())*1000,$dt['mmol_l']]);
        }
        $jsonResponse['mg_dl']=$mg_dl;
        $jsonResponse['mmol_l']=$mmol_l;
        $jsonResponse['max_min']=$min_max;
        return $jsonResponse;
    }

    public function getIotBP($gateway,$from,$to){
        $jsonResponse=array();
        $diastolic=array();
        $pulse=array();
        $systolic=array();
        $data=($this->bp_repo->findByBucket($gateway,$from,$to));
        $min_max=($this->bp_repo->findMaxMinByBucket($gateway,$from,$to));
        foreach($data as $dt){
            array_push($diastolic,[($dt['collect_time']->getTimestamp())*1000,$dt['diastolic']]);
            array_push($pulse,[($dt['collect_time']->getTimestamp())*1000,$dt['pulse']]);
            array_push($systolic,[($dt['collect_time']->getTimestamp())*1000,$dt['systolic']]);
        }
        $jsonResponse['diastolic']=$diastolic;
        $jsonResponse['pulse']=$pulse;
        $jsonResponse['systolic']=$systolic;
        $jsonResponse['max_min']=$min_max;
        return $jsonResponse;
    }
}