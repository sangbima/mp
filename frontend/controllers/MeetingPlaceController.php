<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Meeting;
use frontend\models\MeetingPlace;
use frontend\models\MeetingPlaceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MeetingPlaceController implements the CRUD actions for MeetingPlace model.
 */
class MeetingPlaceController extends Controller
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
     * Lists all MeetingPlace models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MeetingPlaceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MeetingPlace model.
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
     * Creates a new MeetingPlace model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
     public function actionCreate($meeting_id)
     {
       $mtg = new Meeting();
       $title = $mtg->getMeetingTitle($meeting_id);
         $model = new MeetingPlace();
         $model->meeting_id= $meeting_id;
         $model->suggested_by= Yii::$app->user->getId();
         $model->status = MeetingPlace::STATUS_SUGGESTED;
         if ($model->load(Yii::$app->request->post())) {
           // convert either google place
           // or place from user frequent list
           // or current location
           // or selected place id 
           // into place_id for form validation
           // validate the form against model rules
           if ($model->validate()) {
               // all inputs are valid
               $model->save();              
               return $this->redirect(['view', 'id' => $model->id]);
           } else {
               // validation failed
               return $this->render('create', [
                   'model' => $model,
                    'title' => $title,
               ]);
           }          
         } else {
           return $this->render('create', [
               'model' => $model,
             'title' => $title,
           ]);          
         }
     }

    /**
     * Updates an existing MeetingPlace model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MeetingPlace model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MeetingPlace model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MeetingPlace the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MeetingPlace::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
