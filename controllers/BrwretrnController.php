<?php

namespace suPnPsu\borrowMaterial\controllers;

use Yii;
use suPnPsu\user\models\User;
use suPnPsu\borrowMaterial\models\Borrowreturn;
use suPnPsu\borrowMaterial\models\Booking;
use suPnPsu\borrowMaterial\models\BorrowreturnSearch;
use suPnPsu\borrowMaterial\models\BookingsubmitedSearch;
use suPnPsu\borrowMaterial\models\BookingapprovedSearch;
use suPnPsu\borrowMaterial\models\BookingsentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\web\Response;
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

/**
 * BorrowreturnController implements the CRUD actions for Borrowreturn model.
 */
class BrwretrnController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public $admincontroller = [20];

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        foreach ($this->admincontroller as $key) {
            array_push(Yii::$app->controller->module->params['adminModule'], $key);
        }

        return true;
        /*
      if(ArrayHelper::isIn(Yii::$app->user->id, Yii::$app->controller->module->params['adminModule'])){
          //echo 'you are passed';
      }else{
          throw new ForbiddenHttpException('You have no right. Must be admin module.');
      }
      */
    }

    /**
     * Lists all Borrowreturn models.
     * @return mixed
     */
    public function actionIndex()
    {

        Yii::$app->view->title = Yii::t('borrow-material', 'รายการอนุมัติ') . ' - ' . Yii::t('borrow-material', Yii::$app->controller->module->params['title']);

        $searchModel = new BorrowreturnSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Borrowreturn model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        Yii::$app->view->title = Yii::t('borrow-material', 'ดูรายละเอียด') . ' : ' . $model->booking_id . ' - ' . Yii::t('borrow-material', Yii::$app->controller->module->params['title']);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Borrowreturn model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        Yii::$app->view->title = Yii::t('borrow-material', 'เพิ่มการอนุมัติ') . ' - ' . Yii::t('borrow-material', Yii::$app->controller->module->params['title']);

        $model = new Borrowreturn();

        /* if enable ajax validate
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }*/

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('addflsh', [
                    'type' => 'success',
                    'duration' => 4000,
                    'icon' => 'glyphicon glyphicon-ok-circle',
                    'message' => Yii::t('borrow-material', 'เพิ่มข้อมูลเรียบร้อย'),
                ]);
                return $this->redirect(['view', 'id' => $model->booking_id]);
            } else {
                Yii::$app->getSession()->setFlash('addflsh', [
                    'type' => 'danger',
                    'duration' => 4000,
                    'icon' => 'glyphicon glyphicon-remove-circle',
                    'message' => Yii::t('borrow-material', 'เพิ่มข้อมูลไม่สำเร็จ'),
                ]);
            }
            return $this->redirect(['view', 'id' => $model->booking_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);


    }

    /**
     * Updates an existing Borrowreturn model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        Yii::$app->view->title = Yii::t('borrow-material', 'ปรับปรุงข้อมูล {modelClass}: ', [
                'modelClass' => 'Borrowreturn',
            ]) . $model->booking_id . ' - ' . Yii::t('borrow-material', Yii::$app->controller->module->params['title']);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('edtflsh', [
                    'type' => 'success',
                    'duration' => 4000,
                    'icon' => 'glyphicon glyphicon-ok-circle',
                    'message' => Yii::t('borrow-material', 'ปรับปรุงข้อมูลเรียบร้อย'),
                ]);
                return $this->redirect(['view', 'id' => $model->booking_id]);
            } else {
                Yii::$app->getSession()->setFlash('edtflsh', [
                    'type' => 'danger',
                    'duration' => 4000,
                    'icon' => 'glyphicon glyphicon-remove-circle',
                    'message' => Yii::t('borrow-material', 'ปรับปรุงข้อมูลไม่สำเร็จ'),
                ]);
            }
            return $this->redirect(['view', 'id' => $model->booking_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);


    }

    /**
     * Deletes an existing Borrowreturn model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        Yii::$app->getSession()->setFlash('edtflsh', [
            'type' => 'success',
            'duration' => 4000,
            'icon' => 'glyphicon glyphicon-ok-circle',
            'message' => Yii::t('borrow-material', 'ลบข้อมูลเรียบร้อย'),
        ]);


        return $this->redirect(['index']);
    }

    /**
     * Finds the Borrowreturn model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Borrowreturn the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Borrowreturn::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionApprovedlist()
    {

        Yii::$app->view->title = Yii::t('borrow-material', 'รายการที่อนุมัติแล้ว') . ' - ' . Yii::t('borrow-material', Yii::$app->controller->module->params['title']);

        $searchModel = new BookingapprovedSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('approvedlist', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSubmitedlist()
    {

        Yii::$app->view->title = Yii::t('borrow-material', 'รายการที่ยื่นจอง') . ' - ' . Yii::t('borrow-material', Yii::$app->controller->module->params['title']);

        $searchModel = new BookingsubmitedSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('submitedlist', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSentlist()
    {

        Yii::$app->view->title = Yii::t('borrow-material', 'รายการที่ส่งมอบแล้ว') . ' - ' . Yii::t('borrow-material', Yii::$app->controller->module->params['title']);

        $searchModel = new BookingsentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('sentlist', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSubmitborrow($id)
    {
        Yii::$app->view->title = Yii::t('borrow-material', 'อนุมัติการยืม') . ' - ' . Yii::t('borrow-material', Yii::$app->controller->module->params['title']);

        $model = new Borrowreturn();

        //$mdluser = User::findOne(Yii::$app->user->id);

        if (($mdlbooking = Booking::findOne($id)) !== null) {
            $model->booking_id = $mdlbooking->id;
            $mdluser = User::findOne($mdlbooking->user_id);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $model->booking_id = $id;
        $model->confirm_staff_id = Yii::$app->user->id;
        $model->confirm_at = date('Y-m-d H:i');
        $model->deliver_staff_id = Yii::$app->user->id;
        $model->deliver_at = date('Y-m-d H:i');
        $model->return_staff_id = Yii::$app->user->id;
        $model->return_at = date('Y-m-d H:i');

        if ($model->load(Yii::$app->request->post())) {
            if ($model->confirm_status == 1) {
                $model->confirm_comment = "";
            }
            if ($model->save()) {

                $mdlbooking->entry_status = 2;
                $mdlbooking->save();

                Yii::$app->getSession()->setFlash('addflsh', [
                    'type' => 'success',
                    'duration' => 4000,
                    'icon' => 'glyphicon glyphicon-ok-circle',
                    'message' => Yii::t('borrow-material', 'อนุมัติเรียบร้อย'),
                ]);
                //return $this->redirect(['view', 'id' => $model->booking_id]);
                return $this->redirect(['submitedlist']);
            } else {
                Yii::$app->getSession()->setFlash('addflsh', [
                    'type' => 'danger',
                    'duration' => 4000,
                    'icon' => 'glyphicon glyphicon-remove-circle',
                    'message' => Yii::t('borrow-material', 'อนุมัติไม่สำเร็จ'),
                ]);
            }
        }

        return $this->render('submitborrow', [
            'model' => $model,
            'mdlbooking' => $mdlbooking,
            'mdluser' => $mdluser,
        ]);


    }

    public function actionSubmitsend($id)
    {
        Yii::$app->view->title = Yii::t('borrow-material', 'ส่งมอบพัสดุ') . ' - ' . Yii::t('borrow-material', Yii::$app->controller->module->params['title']);

        $model = $this->findModel($id);

        if (($mdlbooking = Booking::findOne($id)) !== null) {
            $model->booking_id = $mdlbooking->id;
            $mdluser = User::findOne($mdlbooking->user_id);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }/**/
        $model->deliver_staff_id = Yii::$app->user->id;
        $model->deliver_at = date('Y-m-d H:i');
        $model->return_staff_id = Yii::$app->user->id;
        $model->return_at = date('Y-m-d H:i');

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {

                $mdlbooking->entry_status = 3;
                $mdlbooking->save();

                Yii::$app->getSession()->setFlash('addflsh', [
                    'type' => 'success',
                    'duration' => 4000,
                    'icon' => 'glyphicon glyphicon-ok-circle',
                    'message' => Yii::t('borrow-material', 'ส่งมอบเรียบร้อย'),
                ]);
                //return $this->redirect(['view', 'id' => $model->booking_id]);
                return $this->redirect(['approvedlist']);
            } else {
                Yii::$app->getSession()->setFlash('addflsh', [
                    'type' => 'danger',
                    'duration' => 4000,
                    'icon' => 'glyphicon glyphicon-remove-circle',
                    'message' => Yii::t('borrow-material', 'ส่งมอบไม่สำเร็จ'),
                ]);
            }
        }

        return $this->render('submitborrow', [
            'model' => $model,
            'mdlbooking' => $mdlbooking,
            'mdluser' => $mdluser,
        ]);
    }

    public function actionSubmitreturn($id)
    {
        Yii::$app->view->title = Yii::t('borrow-material', 'รับคืนพัสดุ') . ' - ' . Yii::t('borrow-material', Yii::$app->controller->module->params['title']);

        $model = $this->findModel($id);

        if (($mdlbooking = Booking::findOne($id)) !== null) {
            $model->booking_id = $mdlbooking->id;
            $mdluser = User::findOne($mdlbooking->user_id);
            $mdluser = User::findOne($mdlbooking->user_id);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }/**/
        $model->return_staff_id = Yii::$app->user->id;
        $model->return_at = date('Y-m-d H:i');

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {

                $mdlbooking->entry_status = 4;
                $mdlbooking->save();

                Yii::$app->getSession()->setFlash('addflsh', [
                    'type' => 'success',
                    'duration' => 4000,
                    'icon' => 'glyphicon glyphicon-ok-circle',
                    'message' => Yii::t('borrow-material', 'รับคืนเรียบร้อย'),
                ]);
                //return $this->redirect(['view', 'id' => $model->booking_id]);
                return $this->redirect(['approvedlist']);
            } else {
                Yii::$app->getSession()->setFlash('addflsh', [
                    'type' => 'danger',
                    'duration' => 4000,
                    'icon' => 'glyphicon glyphicon-remove-circle',
                    'message' => Yii::t('borrow-material', 'รับคืนไม่สำเร็จ'),
                ]);
            }
        }

        return $this->render('submitborrow', [
            'model' => $model,
            'mdlbooking' => $mdlbooking,
            'mdluser' => $mdluser,
        ]);


    }
}
