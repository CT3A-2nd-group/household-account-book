<?php

class TermsController extends BaseController
{
    public function show(): void
    {
        // 利用規約（HTML）をそのまま表示
        include_once __DIR__ . '/../../views/auth/terms.html';
    }
}
