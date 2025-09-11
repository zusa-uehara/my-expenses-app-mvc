<?php
class MyExpenses extends DatabaseModel {

public function fetchMonthlyTotals() {
    return $this->fetchAll("SELECT to_char(date, 'YYYY-MM') AS month, SUM(cost) AS total
                            FROM my_expenses
                            GROUP BY month
                            ORDER BY month DESC
                            LIMIT 6");
}

    public function fetchAllExpenses() {
        return $this->fetchAll('SELECT * FROM my_expenses ORDER BY date DESC');
    }

    public function fetchById($id) {
        $stmt = $this->pdo->prepare('SELECT * FROM my_expenses WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($date, $cost, $category, $memo) {
        $stmt = $this->pdo->prepare('INSERT INTO my_expenses (date, cost, category, memo) VALUES (?, ?, ?, ?)');
        $stmt->execute([$date, $cost, $category, $memo]);
    }

    public function update($id, $date, $cost, $category, $memo) {
        $stmt = $this->pdo->prepare('UPDATE my_expenses SET date=?, cost=?, category=?, memo=? WHERE id=?');
        $stmt->execute([$date, $cost, $category, $memo, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare('DELETE FROM my_expenses WHERE id=?');
        $stmt->execute([$id]);
    }
}
