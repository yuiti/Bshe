<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * Copyright(c) since 2009,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_View
 * @copyright  since 2009,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */

/** Bshe_Log */
require_once 'Bshe/Log.php';
/** Bshe_View_Plugin_Jquery_Treeview_Abstract */
require_once "Bshe/View/Plugin/Jquery/Treeview/Abstract.php";
/**
 * JqueryのTreeviewプラグ引でのfiletree用のクラス
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.05.18
 * @license LGPL
 */
class Bshe_View_Plugin_Jquery_Treeview_Filetree extends Bshe_View_Plugin_Jquery_Treeview_Abstract
{



    /**
     * 初期設定を行うコンストラクタ
     * $param = array(
     *     'path' => treeview表示するパス
     * )
     *
     * @param $params 設定配列
     * @return unknown_type
     */
    public function __construct($params = array())
    {
        try {
            // パラメーター項目設定
            $this->_params['path'] = '';
            $this->_params['relative'] = '';

            parent::__construct($params);

            $this->setArrayTree();

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * $_params['path']にセットされたパスから再帰的にファイルリストを取得し、
     * 配列へ保持する。
     * 配下に配列がある場合は、再帰的にクラスをインスタンス化する
     *
     *
     * @return unknown_type
     */
    public function setArrayTree()
    {
        try {
            $dirScan = scandir($this->getParam('path'));
            foreach ($dirScan as $key => $file) {

                if (substr($file, 0, 1) == '.') {
                    // ..や.をスキップ
                } else {
                    // フォルダかファイル化を判別
                    if (is_dir($this->getParam('path') . '/' . $file)) {
                        // フォルダ
                        $className = get_class($this);
                        $this->_arrayTree[$file] = New $className(array(
                                'path' => $this->getParam('path') . '/' . $file,
                                'self' => $this->getParentString($file),
                                'relative' => $this->getParam('relative') . '/' . $file
                            )
                        );
                    } else {
                        // ファイル
                        $this->setFileString($file);
                    }
                }
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 子供がある場合のTreeに対する文字列を取得する。
     * 実装の際にaタグや文字列を出す際はオーバーライドする。
     *
     * @param $file
     * @return unknown_type
     */
    protected function getParentString($file)
    {
        try {
            return $file;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ファイルパスから、$_arrayTreeにセットする文字列を取得する。
     * 実装の際にaタグや文字列を出す際はオーバーライドする。
     *
     * @param $file
     * @return unknown_type
     */
    protected function setFileString($file)
    {
        try {
            $this->_arrayTree[$file] = $file;
        } catch (Exception $e) {
            throw $e;
        }
    }
}