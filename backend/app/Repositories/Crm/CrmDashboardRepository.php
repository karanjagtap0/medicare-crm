<?php

namespace App\Repositories\Crm;

use App\Interfaces\Crm\CrmDashboardRepositoryInterface;
use App\Models\Customer;
use App\Models\CustomerFollowup;
use App\Models\SupportTicket;
use App\Models\CustomerCommunication;
use Carbon\Carbon;

class CrmDashboardRepository implements CrmDashboardRepositoryInterface
{
    public function getDashboardStats()
    {
        $today = Carbon::today();

        $totalCustomers = Customer::count();
        
        $newCustomersThisMonth = Customer::whereMonth('created_at', Carbon::now()->month)
                                         ->whereYear('created_at', Carbon::now()->year)
                                         ->count();

        $openTickets = SupportTicket::whereIn('status', ['Open', 'In Progress'])->count();

        $todaysFollowups = CustomerFollowup::whereDate('followup_date', $today)
                                           ->where('status', 'Pending')
                                           ->count();

        $recentCommunications = CustomerCommunication::where('created_at', '>=', Carbon::now()->subDays(7))->count();

        return [
            'total_customers' => $totalCustomers,
            'new_customers_this_month' => $newCustomersThisMonth,
            'open_tickets' => $openTickets,
            'todays_pending_followups' => $todaysFollowups,
            'recent_communications_7_days' => $recentCommunications
        ];
    }
}
