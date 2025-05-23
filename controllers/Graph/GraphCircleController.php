<?php
    class GraphLineController extends BaseController {
    
        public function incomeCircle(): void {
            $data = $this->aggregateByMonth('incomes');
            $this->json($data);
        }

        public function expenditureCircle(): void {
            $data = $this->aggregateByMonth('expenditures');
            $this->json($data);
        }

        public function view(): void {
            if (!isset($_SESSION['user_id'])) $this->redirect('/login');
            $this->render('graph/circle', array_merge(
                ['title' => 'カテゴリー別円グラフ']
            ));
        }

        private function aggregateByMonth(string $table): array {
            
        }



    }