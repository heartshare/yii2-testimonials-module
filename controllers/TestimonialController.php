<?php
/**
 * @copyright Copyright &copy; Caleb Crosby, CICSolutions.com, 2014
 * @package yii2-testimonials
 * @version 1.0.0
 */

namespace frontend\controllers;

use Yii;
use frontend\models\Testimonial;
use frontend\models\search\TestimonialSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * TestimonialController implements the CRUD actions for Testimonial model.
 */
class TestimonialController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Testimonial models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TestimonialSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Testimonial model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Testimonial model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
    	// restrict access
		if (Yii::$app->user->isGuest)
		    $this->redirect(['user/login', 'return' => Yii::$app->request->url]);

		// get model
        $model = new Testimonial();
		
		// check for post data
        if ($model->load(Yii::$app->request->post())) {

			// capture the image locally
			$upload_image = UploadedFile::getInstance($model, 'image');

			// set the filename to the modal image field
//			$model->filename = $image->name; // used if storing the source filename before creating random filename
			$ext = end((explode(".", $upload_image->name)));

            // generate a unique file name
            $model->image = Yii::$app->getSecurity()->generateRandomKey().".{$ext}";

            // the path to save file, you can set an uploadPath
            $path = Yii::$app->params['uploadPath'] . 'testimonial_images/' . $model->image;

            // save the modal
            if($model->save()){
                $upload_image->saveAs($path);
                Yii::$app->session->setFlash('success', 'Testimonial saved! Thank you for your feedback!');
                return $this->redirect(['index']);
            } else {
                // error in saving model
                Yii::$app->session->setFlash('error', 'There was a problem saving your testimonial.  Please try again.');
                return $this->redirect(['create']);
            }

        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Testimonial model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {

		if (Yii::$app->user->isGuest)
		    $this->redirect(['user/login', 'return' => Yii::$app->request->url]);

        $model = $this->findModel($id);

		// get model image before loading post data
		$previous_image = $model->image;

        if ($model->load(Yii::$app->request->post())) {
			
			// handle image upload
			$upload_image = UploadedFile::getInstance($model, 'image');
			
			// set model image to what was saved previously before process an upload
			$model->image = $previous_image;

			if (!empty($upload_image->name)) {
				
				// get upload file extension
				$ext = end((explode(".", $upload_image->name)));

	            // generate a unique file name
	            $model->image = Yii::$app->getSecurity()->generateRandomKey().".{$ext}";

	            // the path to save file - CICS NOTE: make the testimonial_images dir be a module param setting
	            $path = Yii::$app->params['uploadPath'] . 'testimonial_images/' . $model->image;
	            
			}
			
			// reset/clear the embed code until processing video url
			$model->video_embed = null;
			
			// handle video embed
			if (!empty($model->video_url)) {

				// lookup video url for embed code
				$model->video_embed = \cics\widgets\AutoEmbed::widget(['url' => $model->video_url, 'return_type' => 'boolean']);

				if (!$model->video_embed) {
					$model->video_embed = null;
					$model->video_url = null;
					
					// set error message that video embed faied
					Yii::$app->session->setFlash('error', 'Could not generate video embed code.  Please try again.');
				}
			}
			
			// handle video upload
			
            // save the model
            if($model->save()){

            	// only save an image if one has been uploaded
				if (!empty($upload_image->name)) {
	                $upload_image->saveAs($path);
				}

				// set success message
                Yii::$app->session->setFlash('success', 'Thank you for your feedback!  We appreciate your input!');

				// redirect to update if only an error on the video embed
/* 				$redirect = isset($model->video_embed) ? ['index'] : ['update', 'id' => $model->id]; */
                return $this->redirect(['index']);

            } else {
            
            	// CICS NOTE: ISN'T THERE A GOOD WAY TO SEND THE USER BACK TO THE FORM WITH THE DATA THEY'VE ENTERED?
            	
                // error in saving model
                Yii::$app->session->setFlash('error', 'There was a problem saving your testimonial.  Please try again.');
                return $this->redirect(['create']);
            }

        } else {
            return $this->render('update', ['model' => $model]);
        }
    }

    /**
     * Deletes an existing Testimonial model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        
        Yii::$app->session->setFlash('success', 'Testimonial deleted.');

        return $this->redirect(['index']);
    }

    /**
     * Finds the Testimonial model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Testimonial the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Testimonial::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	// this could be a good way of checking if the user is allowed to submit a testimonial
	// could check if the current page if the form page, and then check module params to see what settings are available for submission permissions
	// or we could just put this on the pages that need it?
/*
	public function beforeAction()
	{
		if (Yii::$app->user->isGuest)
		    $this->redirect(['user/register']);
		
		//something code right here if user valided
	}    
*/
}
