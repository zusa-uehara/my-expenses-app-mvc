<?php
class ExpensesController extends Controller {


    public function index() {

        $expenses = $this->databaseManager->get('MyExpenses')->fetchAllExpenses();

        return $this->render([
            'title' => '支出登録',
            'rows' => $expenses,
        ]);
    }

    public function create() {
        $errors = [];

        $expensesModel = $this->databaseManager->get('MyExpenses');
        $expenses = $this->databaseManager->get('MyExpenses')->fetchAllExpenses();

        if ($this->request->isPost()) {
            $date = $_POST['date'] ?? '';
            $cost = $_POST['cost'] ?? '';
            $category = $_POST['category'] ?? '';
            $memo = $_POST['memo'] ?? '';

            $valid_categories = ['rent'=>'家賃','utilities'=>'光熱費','living'=>'生活費・雑費','entertainment'=>'交際費','medical'=>'医療費'];

            if (!$date) $errors[] = "日付を入力してください";
            if (!is_numeric($cost) || $cost < 0) $errors[] = "金額は0以上の数字で入力してください";
            if (!array_key_exists($category, $valid_categories)) $errors[] = "不正なカテゴリです";
            if (strlen($memo) > 200) $errors[] = "メモは200文字以内で入力してください";

            if (empty($errors)) {
                $expensesModel->insert($date, $cost, $category, $memo);

            // POST-Redirect-GET: 二重送信防止 & 最新一覧を取得
            header("Location: /expenses");
            exit;
            }
        }

    // 登録成功していないとき、またはエラーがあったときだけここに来る
    $expenses = $expensesModel->fetchAllExpenses();

        return $this->render([
            'title' => '支出登録',
            'errors' => $errors,
            'rows' => $expenses,
        ], 'index');
    }
}
