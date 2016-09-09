<?php

namespace backend\modules\borrowreturn\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Booking[] $bookings
 * @property Borrowreturn[] $borrowreturns
 * @property Borrowreturn[] $borrowreturns0
 * @property Borrowreturn[] $borrowreturns1
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'email', 'created_at', 'updated_at'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(Booking::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBorrowreturns()
    {
        return $this->hasMany(Borrowreturn::className(), ['confirm_staff_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBorrowreturns0()
    {
        return $this->hasMany(Borrowreturn::className(), ['deliver_staff_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBorrowreturns1()
    {
        return $this->hasMany(Borrowreturn::className(), ['return_staff_id' => 'id']);
    }
}
