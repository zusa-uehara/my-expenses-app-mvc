<?php
class DashboardController extends Controller {

    public function index() {
        $expensesModel = $this->databaseManager->get('MyExpenses');
        $results = $expensesModel->fetchMonthlyTotals();


        $months = array_reverse(array_column($results, 'month'));
        $totals = array_reverse(array_column($results, 'total'));


        return $this->render([
            'title' => '今日の支出メモ',
            'months' => $months,
            'totals' => $totals,

        ]); // layout はデフォルトで 'layout'
    }
}
