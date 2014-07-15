<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "testimonial".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $url
 * @property string $title
 * @property string $text
 * @property string $image
 * @property string $video_url
 * @property string $video_embed
 * @property integer $anonymous
 * @property string $created_at
 * @property integer $order
 * @property integer $status
 */
class Testimonial extends \yii\db\ActiveRecord
{
	public $upload_image;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'testimonial';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'anonymous'], 'integer'],
            [['text'], 'string'],
            [['created_at','image'], 'safe'],
            [['name', 'url', 'title', 'video_url'], 'string', 'max' => 255],
            ['email', 'email'],
//            [['video_url', 'url'], 'url'],
//            [['phone'], 'string', 'max' => 45],
            [['image'], 'file', 'mimeTypes' => 'image/jpeg, image/png'],
            [['name', 'email'], 'required'],
		    [['name', 'email', 'url', 'title', 'video_url'], 'trim'],
//		    [['username', 'email'], 'default'], // sets blank fields to null, but not sure how I want to use it yet.  would be good to make blank fields null :)
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'name' => 'Name',
            'email' => 'Email',
//            'phone' => 'Phone',
            'url' => 'Url',
            'title' => 'Title',
            'text' => 'Text',
            'image' => 'Image',
            'video_url' => 'Video Url',
            'video_embed' => 'Video Embed',
            'anonymous' => 'Anonymous',
            'created_at' => 'Created At',
            'order' => 'Order',
            'status' => 'Status',
        ];
    }
}
