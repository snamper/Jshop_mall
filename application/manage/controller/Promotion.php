<?php

namespace app\Manage\controller;

use app\common\controller\Manage;
use app\common\model\Promotion as PromotionModel;
use app\common\model\PromotionCondition;
use app\common\model\PromotionResult;
use Request;
use app\common\model\GoodsCat;

class Promotion extends Manage
{

    /**
     *
     * User:sin
     * @return mixed
     */
    public function index()
    {
        if(Request::isAjax()) {
            $promotionModel = new PromotionModel();
            $request = input('param.');
            $request['type'] = $promotionModel::TYPE_PROMOTION;

            return $promotionModel->tableData($request);
        }
        return $this->fetch();

    }

    /**
     * @return mixed
     */
    public function coupon()
    {
        if(Request::isAjax()) {
            $promotionModel = new PromotionModel();
            $request = input('param.');
            $request['type'] = $promotionModel::TYPE_COUPON;

            return $promotionModel->tableData($request);
        }
        return $this->fetch();

    }

    //添加促销
    public function add()
    {

        if(Request::isPost()){
            if(!input('?param.name')){
                return error_code(15001);
            }
            if(!input('?param.date') || !input('param.date')){
                return error_code(15002);
            }else{
                $theDate = explode(' 到 ',input('param.date'));
                if(count($theDate) != 2){
                    return error_code(15002);
                }
            }
            $data['name'] = input('param.name');
            $data['stime'] = strtotime($theDate[0]);
            $data['etime'] = strtotime($theDate[1]);
            $data['status'] = input('param.status/d',1);
            $data['sort'] = input('param.sort/d',100);
            $data['exclusive'] = input('param.exclusive/d',1);
            $promotionModel = new PromotionModel();
            $id = $promotionModel->insertGetId($data);
            return [
                'status' => true,
                'data' => url('promotion/edit',['id'=>$id]),
                'msg' => ''
            ];
        }
        return $this->fetch();
    }
    //添加优惠券
    public function couponAdd()
    {
        if(Request::isPost()){
            if(!input('?param.name')){
                return error_code(15001);
            }
            if(!input('?param.date') || !input('param.date')){
                return error_code(15002);
            }else{
                $theDate = explode(' 到 ',input('param.date'));
                if(count($theDate) != 2){
                    return error_code(15002);
                }
            }
            $promotionModel = new PromotionModel();
            $data['name'] = input('param.name');
            $data['stime'] = strtotime($theDate[0]);
            $data['etime'] = strtotime($theDate[1]);
            $data['status'] = input('param.status/d',1);
            $data['auto_receive'] = input('param.auto_receive/d',2);
            $data['sort'] = input('param.sort/d',100);
            $data['type'] = $promotionModel::TYPE_COUPON;

            $id = $promotionModel->insertGetId($data);
            return [
                'status' => true,
                'data' => url('promotion/couponEdit',['id'=>$id]),
                'msg' => ''
            ];
        }
        return $this->fetch('couponAdd');
    }

    //编辑促销
    public function edit()
    {
        $promotionModel = new PromotionModel();
        $where['id'] = input('param.id');
        $where['type'] = $promotionModel::TYPE_PROMOTION;
        $info = $promotionModel->where($where)->find();
        if(!$info){
            $this->error('没有找到此促销记录');
        }

        if(Request::isPost()){
            if(!input('?param.name')){
                return error_code(15001);
            }
            if(!input('?param.date') || !input('param.date')){
                return error_code(15002);
            }else{
                $theDate = explode(' 到 ',input('param.date'));
                if(count($theDate) != 2){
                    return error_code(15002);
                }
            }
            $data['name'] = input('param.name');
            $data['stime'] = strtotime($theDate[0]);
            $data['etime'] = strtotime($theDate[1]);
            $data['status'] = input('param.status/d',2);
            $data['sort'] = input('param.sort/d',100);
            $data['exclusive'] = input('param.exclusive/d',1);
            $promotionModel = new PromotionModel();
            $id = $promotionModel->where($where)->update($data);
            return [
                'status' => true,
                'data' => url('promotion/edit',['id'=>$id]),
                'msg' => ''
            ];
        }

        $this->assign('info',$info);

        return $this->fetch();
    }
    //编辑优惠券
    public function couponEdit()
    {
        $promotionModel = new PromotionModel();
        $where['id'] = input('param.id');
        $where['type'] = $promotionModel::TYPE_COUPON;
        $info = $promotionModel->where($where)->find();
        if(!$info){
            $this->error('没有找到此优惠券记录');
        }

        if(Request::isPost()){
            if(!input('?param.name')){
                return error_code(15001);
            }
            if(!input('?param.date') || !input('param.date')){
                return error_code(15002);
            }else{
                $theDate = explode(' 到 ',input('param.date'));
                if(count($theDate) != 2){
                    return error_code(15002);
                }
            }
            $data['name'] = input('param.name');
            $data['stime'] = strtotime($theDate[0]);
            $data['etime'] = strtotime($theDate[1]);
            $data['status'] = input('param.status/d',2);
            $data['sort'] = input('param.sort/d',100);
            $data['exclusive'] = input('param.exclusive/d',1);
            $data['auto_receive'] = input('param.auto_receive/d',2);
            $promotionModel = new PromotionModel();
            $id = $promotionModel->where($where)->update($data);
            return [
                'status' => true,
                'data' => url('promotion/edit',['id'=>$id]),
                'msg' => ''
            ];
        }

        $this->assign('info',$info);

        return $this->fetch('couponEdit');
    }
    public function del()
    {
        $promotionModel = new PromotionModel();
        $where['id'] = input('param.id');
        $info = $promotionModel->where($where)->find();
        if(!$info){
            return error_code(10002);
        }
        if($promotionModel::destroy($info['id'])){
            return [
                'status' => true,
                'data' => '',
                'msg' => ''
            ];
        }else{
            return error_code(10007);
        }
    }
    public function couponDel()
    {
        $promotionModel = new PromotionModel();
        $where['id'] = input('param.id');
        $info = $promotionModel->where($where)->find();
        if(!$info){
            return error_code(10002);
        }
        if($promotionModel::destroy($info['id'])){
            return [
                'status' => true,
                'data' => '',
                'msg' => ''
            ];
        }else{
            return error_code(10007);
        }
    }


    //条件列表
    public function conditionList()
    {
        $conditionModel = new PromotionCondition();
        if(!input('?param.id')){
            return error_code(10003);
        }

        //校验是否有此权限
        $promotionModel = new PromotionModel();
        $pwhere['id'] = input('param.id');
        $info = $promotionModel->where($pwhere)->find();
        if(!$info){
            return error_code(10002);
        }

        //$where['id'] = input('param.id');
        $where['promotion_id'] = input('param.id');
        return $conditionModel->tableData($where);
    }
    //单纯的选择促销条件
    public function conditionAdd()
    {
        $this->view->engine->layout(false);
        $conditionModel = new PromotionCondition();
        $this->assign('code',$conditionModel->code);
        return [
            'status' => true,
            'data' => $this->fetch('conditionAdd'),
            'msg' => ''
        ];
    }
    //添加促销条件
    public function conditionEdit()
    {
        $this->view->engine->layout(false);

        if(!(input('?param.condition_code')&& input('?param.promotion_id')) && !input('?param.id')){
            return error_code(15003);
        }

        //校验是否有此权限
        $promotionModel = new PromotionModel();
        $pwhere['id'] = input('param.promotion_id');
        $pinfo = $promotionModel->where($pwhere)->find();
        if(!$pinfo){
            return error_code(10002);
        }

        $conditionModel = new PromotionCondition();

        if(Request::isPOST()){
            $data = input('param.');
            return $conditionModel->addData($data);
        }

        //如果是修改，就取数据，否则就是新增，直接渲染模板
        if(input('?param.id')){
            $info = $conditionModel->getInfo(input('param.id'));
            if(!$info){
                return error_code(15004);
            }
            $code = $info['code'];
            $this->assign($info->toArray());
        }else{
            $code = input('param.condition_code');
            $this->assign('promotion_id',input('param.promotion_id/d'));
            $this->assign('code',$code);
        }

        //初始化数据
        switch ($code){
            case 'GOODS_CATS':
                $goodsCatModel = new GoodsCat();
                $catList       = $goodsCatModel->getCatByParentId(0);
                $this->assign('catList', $catList);

                break;
        }


        return [
            'status' => true,
            'data' => $this->fetch('promotion/condition/'.$code),
            'msg' => ''
        ];
    }
    //促销条件删除
    public function conditionDel()
    {
        //校验是否有此权限
        $promotionModel = new PromotionModel();
        $pwhere['id'] = input('param.promotion_id');
        $info = $promotionModel->where($pwhere)->find();
        if(!$info){
            return error_code(10002);
        }

        $conditionModel = new PromotionCondition();
        return $conditionModel->toDel(input('param.id'));
    }

    //促销结果列表
    public function resultList()
    {
        $resultModel = new PromotionResult();
        if(!input('?param.id')){
            return error_code(10003);
        }

        //校验是否有此权限
        $promotionModel = new PromotionModel();
        $pwhere['id'] = input('param.id');
        $info = $promotionModel->where($pwhere)->find();
        if(!$info){
            return error_code(10002);
        }



        //$where['id'] = input('param.id');
        $where['promotion_id'] = input('param.id');
        return $resultModel->tableData($where);
    }
    //单纯的选择促销结果
    public function resultAdd()
    {
        $this->view->engine->layout(false);
        $resultModel = new PromotionResult();
        $this->assign('code',$resultModel->code);
        return [
            'status' => true,
            'data' => $this->fetch('resultAdd'),
            'msg' => ''
        ];
    }
    //添加促销条件
    public function resultEdit()
    {
        $this->view->engine->layout(false);

        if(!(input('?param.result_code')&& input('?param.promotion_id')) && !input('?param.id')){
            return error_code(15003);
        }

        //校验是否有此权限
        $promotionModel = new PromotionModel();
        $pwhere['id'] = input('param.promotion_id');
        $info = $promotionModel->where($pwhere)->find();
        if(!$info){
            return error_code(10002);
        }

        $resultModel = new PromotionResult();


        if(Request::isPOST()){$data = input('param.');
            return $resultModel->addData($data);
        }

        //如果是修改，就取数据，否则就是新增，直接渲染模板
        if(input('?param.id')){
            $info = $resultModel->getInfo(input('param.id'));
            if(!$info){
                return error_code(15004);
            }
            $code = $info['code'];
            $this->assign($info->toArray());
        }else{
            $code = input('param.result_code');
            $this->assign('promotion_id',input('param.promotion_id/d'));
            $this->assign('code',$code);
        }


        return [
            'status' => true,
            'data' => $this->fetch('promotion/result/'.$code),
            'msg' => ''
        ];
    }
    //促销条件删除
    public function resultDel()
    {
        //校验是否有此权限
        $promotionModel = new PromotionModel();
        $pwhere['id'] = input('param.promotion_id');
        $info = $promotionModel->where($pwhere)->find();
        if(!$info){
            return error_code(10002);
        }

        $resultModel = new PromotionResult();
        return $resultModel->toDel(input('param.id'));
    }

    //优惠券下载
    public function couponUpload()
    {
        if(!input('?param.id')){
            return error_code(10000);
        }



        $this->assign('id',input('param.id'));
        return [
            'status' => true,
            'data' => $this->fetch('resultAdd'),
            'msg' => ''
        ];
    }

}