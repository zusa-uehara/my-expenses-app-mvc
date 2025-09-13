<?php
class DashboardController extends Controller {

    public function index() {
        $expensesModel = $this->databaseManager->get('MyExpenses');
        $results = $expensesModel->fetchMonthlyTotals();

        $months = array_reverse(array_column($results, 'month'));
        $totals = array_reverse(array_column($results, 'total'));


        return $this->render([
            'title' => '登録・変更・月間グラフ',
            'months' => $months,
            'totals' => $totals,
        ]);
    }
}
