<?php

namespace app\controllers;

use Yii;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $isGuest = Yii::$app->user->isGuest;
        $isAdmin = ((!$isGuest)&&(Yii::$app->user->identity->user_type == 0));
        $query = '';
        
        if(!$isGuest){
            $query = $isAdmin ?
                User::find()://->where(['user_type' => 1]) :
                User::find()->where(['user_id' => Yii::$app->User->identity->user_id]);
        }        
        
        // Return students only
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'isGuest' => $isGuest,
            'isAdmin' => $isAdmin
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $success = false;
        
        $model = new User();
        
        if ($model->load(Yii::$app->request->post())){            
            
            $model->password = md5($model->password);
        
            if($model->save()){
                $success = true;
            }
        }   
        
        if($success){
            return $this->redirect(['index']);
        } else {            
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $success = false;
        
        $model = $this->findModel($id); 
        
        $oldPassword = $model->password;
        
        if ($model->load(Yii::$app->request->post())){            
            
            if($model->password!=$oldPassword){
                $model->password = md5($model->password);
            }
        
            if($model->save()){
                $success = true;
            }
        }   
        
        if($success){
            return $this->redirect(['view', 'student_no' => $model->student_no]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }   
    }

    /**
     * Deletes an existing User model.
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
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}