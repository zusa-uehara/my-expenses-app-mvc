<?php
class EditController extends Controller {

    public function index() {

    $expensesModel = $this->databaseManager->get('MyExpenses');
    return $this->render([
        'title' => '支出変更',
    ]);
}


    public function edit() {
           $expensesModel = $this->databaseManager->get('MyExpenses');

    // まだ id を受け取らない → 新規入力用に空データを用意
    $row = [
        'date' => '',
        'cost' => '',
        'category' => '',
        'memo' => ''
    ];

    $errors = [];
    $success_message = '';

    if ($this->request->isPost()) {
        $action = $_POST['action'] ?? '';

        if ($action === 'update') {
            $date = $_POST['date'] ?? '';
            $cost = $_POST['cost'] ?? '';
            $category = $_POST['category'] ?? '';
            $memo = $_POST['memo'] ?? '';

            $valid_categories = [
                'rent'=>'家賃',
                'utilities'=>'光熱費',
                'living'=>'生活費・雑費',
                'entertainment'=>'交際費',
                'medical'=>'医療費'
            ];

            if (!$date) $errors[] = "日付を入力してください";
            if (!is_numeric($cost) || $cost < 0) $errors[] = "金額は0以上の数字で入力してください";
            if (!array_key_exists($category, $valid_categories)) $errors[] = "不正なカテゴリです";
            if (strlen($memo) > 200) $errors[] = "メモは200文字以内で入力してください";

            if (empty($errors)) {
                // insert用のメソッドを用意
                $expensesModel->insert($date, $cost, $category, $memo);
                $success_message = "支出を登録しました";
            }

        } elseif ($action === 'delete') {
            // idがないので delete は無効
            $errors[] = "削除対象がありません";
        }
    }

    return $this->render([
        'title' => '支出入力',
        'row' => $row,
        'errors' => $errors,
        'success_message' => $success_message
    ], 'index');
}
}
