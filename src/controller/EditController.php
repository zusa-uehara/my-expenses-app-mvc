<?php
class EditController extends Controller {

    // -------------------------
    // 一覧表示
    // -------------------------
    public function index() {

        $expensesModel = $this->databaseManager->get('MyExpenses');
        $rows = $expensesModel->fetchAllExpenses();

        return $this->render([
            'title' => '変更・支出一覧',
            'rows' => $rows
        ]);
    }

    // -------------------------
    // 編集フォーム表示
    // -------------------------
    public function edit() {

        $expensesModel = $this->databaseManager->get('MyExpenses');
        $rows = $expensesModel->fetchAllExpenses();
        $errors = [];

        $id = $_POST['id'] ?? null;

        $row = [
            'id' => $id ?? '',
            'date' => '',
            'cost' => '',
            'category' => '',
            'memo' => ''
        ];

        if ($id) {
            $data = $expensesModel->fetchById($id);
            if ($data) {
                $row = $data;
            } else {
                $errors[] = "指定されたIDの支出は見つかりませんでした。";
            }
        } else {
            $errors[] = "IDが指定されていません。";
        }

        $valid_categories = [
            'rent'=>'家賃',
            'utilities'=>'光熱費',
            'living'=>'生活費・雑費',
            'entertainment'=>'交際費',
            'medical'=>'医療費'
        ];

        return $this->render([
            'title' => 'ID選択・支出一覧',
            'row' => $row,
            'rows' => $rows,
            'errors' => $errors,
            'success_message' => '',
			'valid_categories' => $valid_categories,
        ], 'edit');
    }

    // -------------------------
    // 更新 or 削除処理
    // -------------------------
    public function update() {
        $expensesModel = $this->databaseManager->get('MyExpenses');
        $errors = [];
        $success_message = '';
        $rows = $expensesModel->fetchAllExpenses();

        $id = $_POST['id'] ?? null;
        $action = $_POST['action'] ?? '';

        if (!$id) $errors[] = "IDが指定されていません。";

        $row = [
            'id' => $id,
            'date' => $_POST['date'] ?? '',
            'cost' => $_POST['cost'] ?? '',
            'category' => $_POST['category'] ?? '',
            'memo' => $_POST['memo'] ?? ''
        ];

        $valid_categories = [
            'rent'=>'家賃',
            'utilities'=>'光熱費',
            'living'=>'生活費・雑費',
            'entertainment'=>'交際費',
            'medical'=>'医療費'
        ];

        if (!$row['date']) $errors[] = "日付を入力してください";
        if (!is_numeric($row['cost']) || $row['cost'] < 0) $errors[] = "金額は0以上の数字で入力してください";
        if (!array_key_exists($row['category'], $valid_categories)) $errors[] = "不正なカテゴリです";
        if (mb_strlen($row['memo']) > 200) $errors[] = "メモは200文字以内で入力してください";

        if (empty($errors)) {
            if ($action === 'update') {
                $expensesModel->update($id, $row['date'], $row['cost'], $row['category'], $row['memo']);
                header("Location: /edit");
                exit;
            } elseif ($action === 'delete') {
                $expensesModel->delete($id);
                header("Location: /edit");
                exit;
            }
        }

        return $this->render([
            'title' => '変更削除・支出一覧',
            'row' => $row,
            'errors' => $errors,
            'success_message' => $success_message,
            'rows' => $rows,
            'valid_categories' => $valid_categories
        ], 'edit');
        }
}
