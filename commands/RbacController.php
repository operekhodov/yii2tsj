<?php

namespace app\commands;
 
use Yii;
use yii\console\Controller;
 
/**
 * RBAC generator
 */
class RbacController extends Controller
{
    /**
     * Generates roles
     */
    public function actionInit()
    {
        $auth = Yii::$app->getAuthManager();
        $auth->removeAll();
		
		//--system--
		
		$rootPanel = $auth->createPermission('rootPanel');
        $rootPanel->description = 'Root panel';
        $auth->add($rootPanel);
        
        $adminPanel = $auth->createPermission('adminPanel');
        $adminPanel->description = 'Admin panel';
        $auth->add($adminPanel);
        
        $moderPanel = $auth->createPermission('moderPanel');
        $moderPanel->description = 'moder Panel';
        $auth->add($moderPanel);
        
        //--company--
		
        $bossPanel = $auth->createPermission('bossPanel');
        $bossPanel->description = 'boss Panel';
        $auth->add($bossPanel);		
		
        $dispatcherPanel = $auth->createPermission('dispatcherPanel');
        $dispatcherPanel->description = 'Dispatcher panel';
        $auth->add($dispatcherPanel);
        
		$specPanel = $auth->createPermission('specPanel');
        $specPanel->description = 'spec Panel';
        $auth->add($specPanel);
        
        $agentPanel = $auth->createPermission('agentPanel');
        $agentPanel->description = 'agent Panel';
        $auth->add($agentPanel);
        
        //--users--
		
        $governmentPanel = $auth->createPermission('governmentPanel');
        $governmentPanel->description = 'Government panel';
        $auth->add($governmentPanel);
        
        $userPanel = $auth->createPermission('userPanel');
        $userPanel->description = 'User panel';
        $auth->add($userPanel);
		
        $halfuserPanel = $auth->createPermission('halfuserPanel');
        $halfuserPanel->description = 'halfuser Panel';
        $auth->add($halfuserPanel);
        
        //--roles--
		//--users--
        $halfuser = $auth->createRole('halfuser');
        $halfuser->description = 'halfuser';
        $auth->add($halfuser);		
		
        $user = $auth->createRole('user');
        $user->description = 'User';
        $auth->add($user);
        
        $government = $auth->createRole('government');
        $government->description = 'Government';
        $auth->add($government);
        
        //--company--
        
        $agent = $auth->createRole('agent');
        $agent->description = 'agent';
        $auth->add($agent);
        
		$spec = $auth->createRole('spec');
        $spec->description = 'spec';
        $auth->add($spec);        
        
        $dispatcher = $auth->createRole('dispatcher');
        $dispatcher->description = 'Dispatcher';
        $auth->add($dispatcher);
        
		$boss = $auth->createRole('boss');
        $boss->description = 'boss';
        $auth->add($boss);        
        
        //--system--
        
        $moder = $auth->createRole('moder');
        $moder->description = 'moder';
        $auth->add($moder);        
        
        $admin = $auth->createRole('admin');
        $admin->description = 'Admin';
        $auth->add($admin);
        
        $root = $auth->createRole('root');
        $root->description = 'root';
        $auth->add($root);
        
        //--
        //--users--
        $auth->addChild($halfuser,$halfuserPanel);
		
		$auth->addChild($user,$halfuser);
		$auth->addChild($user,$halfuserPanel);
		$auth->addChild($user,$userPanel);
		
		$auth->addChild($government,$halfuser);
		$auth->addChild($government,$halfuserPanel);		
		$auth->addChild($government,$user);
		$auth->addChild($government,$userPanel);
		$auth->addChild($government,$governmentPanel);
		
		//--company--
		
		$auth->addChild($spec,$halfuser);
		$auth->addChild($spec,$halfuserPanel);		
		$auth->addChild($spec,$user);
		$auth->addChild($spec,$userPanel);
		$auth->addChild($spec,$government);
		$auth->addChild($spec,$governmentPanel);		
		$auth->addChild($spec,$specPanel);
		
		$auth->addChild($agent,$halfuser);
		$auth->addChild($agent,$halfuserPanel);
		$auth->addChild($agent,$user);
		$auth->addChild($agent,$userPanel);
		$auth->addChild($agent,$government);
		$auth->addChild($agent,$governmentPanel);
		$auth->addChild($agent,$spec);	
		$auth->addChild($agent,$specPanel);
		$auth->addChild($agent,$agentPanel);
		
		$auth->addChild($dispatcher,$halfuser);
		$auth->addChild($dispatcher,$halfuserPanel);		
		$auth->addChild($dispatcher,$user);
		$auth->addChild($dispatcher,$userPanel);
		$auth->addChild($dispatcher,$government);
		$auth->addChild($dispatcher,$governmentPanel);
		$auth->addChild($dispatcher,$spec);	
		$auth->addChild($dispatcher,$specPanel);
		$auth->addChild($dispatcher,$agent);	
		$auth->addChild($dispatcher,$agentPanel);		
		$auth->addChild($dispatcher,$dispatcherPanel);
		
		$auth->addChild($boss,$halfuser);
		$auth->addChild($boss,$halfuserPanel);		
		$auth->addChild($boss,$user);
		$auth->addChild($boss,$userPanel);
		$auth->addChild($boss,$government);
		$auth->addChild($boss,$governmentPanel);
		$auth->addChild($boss,$spec);	
		$auth->addChild($boss,$specPanel);
		$auth->addChild($boss,$agent);	
		$auth->addChild($boss,$agentPanel);	
		$auth->addChild($boss,$dispatcher);	
		$auth->addChild($boss,$dispatcherPanel);
		$auth->addChild($boss,$bossPanel);
		
		//--system--
		
		$auth->addChild($moder,$halfuser);
		$auth->addChild($moder,$halfuserPanel);		
		$auth->addChild($moder,$user);
		$auth->addChild($moder,$userPanel);
		$auth->addChild($moder,$government);
		$auth->addChild($moder,$governmentPanel);
		$auth->addChild($moder,$spec);	
		$auth->addChild($moder,$specPanel);
		$auth->addChild($moder,$agent);	
		$auth->addChild($moder,$agentPanel);	
		$auth->addChild($moder,$dispatcher);	
		$auth->addChild($moder,$dispatcherPanel);
		$auth->addChild($moder,$boss);
		$auth->addChild($moder,$bossPanel);
		$auth->addChild($moder,$moderPanel);
		
		$auth->addChild($admin,$halfuser);
		$auth->addChild($admin,$halfuserPanel);		
		$auth->addChild($admin,$user);
		$auth->addChild($admin,$userPanel);
		$auth->addChild($admin,$government);
		$auth->addChild($admin,$governmentPanel);
		$auth->addChild($admin,$spec);	
		$auth->addChild($admin,$specPanel);
		$auth->addChild($admin,$agent);	
		$auth->addChild($admin,$agentPanel);	
		$auth->addChild($admin,$dispatcher);	
		$auth->addChild($admin,$dispatcherPanel);
		$auth->addChild($admin,$boss);
		$auth->addChild($admin,$bossPanel);
		$auth->addChild($admin,$moder);
		$auth->addChild($admin,$moderPanel);
		$auth->addChild($admin,$adminPanel);
		
		$auth->addChild($root,$halfuser);
		$auth->addChild($root,$halfuserPanel);		
		$auth->addChild($root,$user);
		$auth->addChild($root,$userPanel);
		$auth->addChild($root,$government);
		$auth->addChild($root,$governmentPanel);
		$auth->addChild($root,$spec);	
		$auth->addChild($root,$specPanel);
		$auth->addChild($root,$agent);	
		$auth->addChild($root,$agentPanel);	
		$auth->addChild($root,$dispatcher);	
		$auth->addChild($root,$dispatcherPanel);
		$auth->addChild($root,$boss);
		$auth->addChild($root,$bossPanel);
		$auth->addChild($root,$moder);
		$auth->addChild($root,$moderPanel);
		$auth->addChild($root,$admin);
		$auth->addChild($root,$adminPanel);
		$auth->addChild($root,$rootPanel);
		
 
        $this->stdout('Done!' . PHP_EOL);
    }
}