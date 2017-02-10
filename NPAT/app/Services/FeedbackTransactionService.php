<?php

namespace App\Services;

use App\Repositories\FeedbackTransactionRepository;

class FeedbackTransactionService
{
    /**
     * @var FeedbackTransactionRepository
     */
    private $feedbackTransactionRepository;

    public function __construct(FeedbackTransactionRepository $feedbackTransactionRepository)
    {
        $this->feedbackTransactionRepository = $feedbackTransactionRepository;
    }

    public function getFeedbackTransactionDataWithMetricsIdAsKey($feedbackId)
    {
        $feedbackTransactionData = [];
        $feedbackTransactions = $this->feedbackTransactionRepository->getFeedbackMetricsDataForEdit($feedbackId);
        foreach ($feedbackTransactions as $feedbackTransaction) {
            $feedbackTransactionData[$feedbackTransaction->feedback_metrics_id] = $feedbackTransaction->toArray();
        }

        return $feedbackTransactionData;
    }
}
