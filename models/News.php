<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\UploadedFile;
use app\models\Mkd;
use app\models\Area;

class News extends \yii\db\ActiveRecord
{
	public $imageFiles;
    const TYPE_NEWS		= 0;
    const TYPE_MESS 	= 1;
    const TYPE_BOARD	= 2;

    public static function tableName()
    {
        return 'news';
    }
    public function rules()
    {
        return [
            [['mkd_ids','id_u', 'title'], 'required'],
            [['id_a','id_u'], 'integer'],
            [['title', 'imagesmas'], 'string', 'max' => 255],
            ['body','string'],
            [['imageFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png,jpg,jpeg,doc,odt,docx,xls,xlsx,txt,csv,pdf', 'maxFiles' => 6],
            [['datetime','datecreated','push','fpage','type','mkd_ids'],'safe'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_a' => Yii::t('app', 'Id A'),
            'title' => Yii::t('app', 'Заголовок'),
            'body' => Yii::t('app', 'Описание'),
            'imagesmas' => Yii::t('app', 'Imagesmas'),
            'note' => Yii::t('app', 'Note'),
            'imageFiles' => Yii::t('app', 'Файл к новости'),
            'id_u' => Yii::t('app', 'Кто добавил'),
            'type' => Yii::t('app', 'TYPE'),
            'datecreated' => Yii::t('app', 'CREATEDDATE'),
            'push' => Yii::t('app', ''),
            'fpage' => Yii::t('app', ''),
            
        ];
    }
	public function getHTMLAddressMkdList($mkd_ids) 
	{
		$mkd_ids = json_decode($mkd_ids);
		foreach($mkd_ids as $id){
			$adr = Mkd::getAddressMkdByID($id);
			$html .= "<span class='label label-primary'>$adr</span> ";
		}
		return $html;
	}
    public function getTypeuseName()
    {
        return ArrayHelper::getValue(self::getTypeuseArray(), $this->type);
    }
    public static function getTypeuseArray()
    {
        return [
            self::TYPE_NEWS		=>  Yii::t('app', 'Новости'),
            self::TYPE_MESS		=>  Yii::t('app', 'Оповещения'),
            self::TYPE_BOARD 	=>  Yii::t('app', 'Доска объявлений'),
        ];
    }       
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_u']);
    }
    public function getUserUsername() 
    {
        return $this->user->fio;
    }
    public function getThisAreaNews($id_a)
    {
    	if(Yii::$app->user->identity->role == 'user' || Yii::$app->user->identity->role == 'government') {
			return self::find()->where(['like','mkd_ids','"'.$id_a.'"'])->andwhere(['fpage' => 1])->orderBy('id DESC')->all();
    	}else{
			return self::find()->where(['id_a' => Yii::$app->user->identity->id_org, 'fpage' => 1])->orderBy('id DESC')->all();
    	}
    }
    public function getThisAreaNewsCount($id_a,$type)
    {
    	if(Yii::$app->user->identity->role == 'user' || Yii::$app->user->identity->role == 'government') {
			return count( self::find()->where(['like','mkd_ids','"'.$id_a.'"'])->andwhere(['type' => $type])->all() );			
    	}else{
			return count( self::find()->where(['id_a' => Yii::$app->user->identity->id_org, 'type' => $type])->all() );
    	}
    }
	private function minstr($body, $count)
	{
		
		if(strlen($body) > $count){
			$body = rtrim($body, "!,.-");
			$body = substr($body, 0, $count);
			$body = substr($body, 0, strrpos($body, ' '))."...";
			$body = strip_tags($body);
		}
		return $body;
	}
	private function in_html($id1,$title1,$body1,$datetime1,$image1,$id2,$title2,$body2,$datetime2,$image2)
	{
		$url1 = Url::toRoute(['news/view', 'id' => $id1]);
		$url2 = Url::toRoute(['news/view', 'id' => $id2]);
		$html = "
		<div class='col-md-1'>
		</div>
		<div class='col-md-10'>
			<div class='row'>
				<div class='col-md-6'>
					<div style='background: url($image1); background-size: cover;padding-top: 50px;'>
						<div class='row'>
							<div class='col-md-12'>
								<span class='pull-left' style='background: #919191;padding: 3px;color: white;'><small>$datetime1</small></span>
							</div>
						</div>
						<div class='row'>
							<div class='col-md-12 title_news'>
								<div id='head_news' style='background:rgba(255, 167, 53, 0.7); padding: 0 5px;'>
									<u><a href='$url1' style='color: black;'><h4>$title1</h4></a></u>
								</div>
							</div>
						</div>
					</div>
					<div class='row'>
						<div class='col-md-12'>
							<p>$body1</p>
						</div>						
					</div>					
				</div>
				<div class='col-md-6'>
					<div style='background: url($image2); background-size: cover;padding-top: 50px;'>
						<div class='row'>
							<div class='col-md-12'>
								<span class='pull-left' style='background: #919191;padding: 3px;color: white;'><small>$datetime2</small></span>
							</div>
						</div>
						<div class='row'>
							<div class='col-md-12 title_news'>
								<div id='head_news' style='background:rgba(255, 255, 255, 0.7); padding: 0 5px;'>
									<u><a href='$url2' style='color: black;'><h4>$title2</h4></a></u>
								</div>
							</div>							
						</div>
					</div>
					<div class='row'>
						<div class='col-md-12'>
							<p>$body2</p>
						</div>						
					</div>
				</div>
			</div>
		</div>
		<div class='col-md-1'>
		</div>
		";
		
		return $html;
	}
	public function getItemsHTML()
	{
		$all_news = News::getThisAreaNews(\Yii::$app->user->identity->id_a);
		$i = 0;
		$html = '';
		$items = array();
		
		$arr_buf = array_keys($all_news);
		for($i=0;$i<count($all_news);$i++){



			
			$title = $all_news[$i]->title;
			$body = $all_news[$i]->body;
			if($i == count($all_news)-1){
				$id1		= $all_news[$i]->id;
				$title1		= News::minstr($all_news[$i]->title,100);
				$body1		= News::minstr($all_news[$i]->body,200);
				$datetime1	= $all_news[$i]->datecreated;
				$arr = json_decode($all_news[$i]->imagesmas);
				if($arr){
					$image1 = substr_replace($arr[0], '/u',0 ,1);
				}else{
					$image1 = '/uploads/news_fon.jpg';
				}
				$id2		= $all_news[0]->id;
				$title2		= News::minstr($all_news[0]->title,100);
				$body2		= News::minstr($all_news[0]->body,200);
				$datetime2	= $all_news[0]->datecreated;
				$arr = json_decode($all_news[0]->imagesmas);
				if($arr){
					$image2 = substr_replace($arr[0], '/u',0 ,1);
				}else{
					$image2 = '';
				}
				$html = News::in_html($id1,$title1,$body1,$datetime1,$image1,$id2,$title2,$body2,$datetime2,$image2);
				array_push($items, ['content' => "$html"]);
			}else{
				$id1	= $all_news[$i]->id;
				$title1	= News::minstr($all_news[$i]->title,100);
				$body1	= News::minstr($all_news[$i]->body,200);
				$datetime1	= $all_news[$i]->datecreated;
				$arr = json_decode($all_news[$i]->imagesmas);
				if($arr){
					$image1 = substr_replace($arr[0], '/u',0 ,1);
				}else{
					$image1 = '/uploads/news_fon.jpg';
				}				
				$id2	= $all_news[$i+1]->id;
				$title2	= News::minstr($all_news[$i+1]->title,100);
				$body2	= News::minstr($all_news[$i+1]->body,200);
				$datetime2	= $all_news[$i+1]->datecreated;
				$arr = json_decode($all_news[$i+1]->imagesmas);
				if($arr){
					$image2 = substr_replace($arr[0], '/u',0 ,1);
				}else{
					$image2 = '/uploads/news_fon.jpg';
				}				
				$html = News::in_html($id1,$title1,$body1,$datetime1,$image1,$id2,$title2,$body2,$datetime2,$image2);
				array_push($items, ['content' => "$html"]);	
			}
		}
		
		return $items;
	}    
}
