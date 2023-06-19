<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Category;
use App\Models\City;
use App\Models\Country;
use App\Models\History;
use App\Models\Report;
use App\Models\Restaurant;
use App\Models\ServiceSubscription;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $year = $request->year == null ? Carbon::now()->format('Y') : $request->year;
        $month = $request->month == null ? Carbon::now()->format('m') : $request->month;
        $registered_restaurants = Report::whereType('restaurant')
            ->whereyear('created_at','=',$year)
            ->whereMonth('created_at','=',$month)
            ->whereStatus('registered')
            ->count();
        $subscribed_restaurants = Report::whereType('restaurant')
            ->whereStatus('subscribed')
            ->whereyear('created_at','=',$year)
            ->whereMonth('created_at','=',$month)
            ->count();
        $registeredNotSubscribed = Restaurant::with('subscription')
            ->whereHas('subscription', function ($e) {
                $e->whereIn('status', ['tentative', 'tentative_finished']);
            })
            ->whereyear('created_at','=',$year)
            ->whereMonth('created_at','=',$month)
            ->orWhereNotIn('id', function ($q) {
                $q->select('restaurant_id')->from('subscriptions');
            })
            ->whereType('restaurant')
            ->whereNotIn('status' , ['inComplete'])
            ->whereyear('created_at','=',$year)
            ->whereMonth('created_at','=',$month)
            ->count();
        $renewed_restaurants = Report::whereType('restaurant')
            ->whereStatus('renewed')
            ->whereyear('created_at','=',$year)
            ->whereMonth('created_at','=',$month)
            ->count();
        $need_renew_restaurants = Restaurant::with('subscription')
            ->whereHas('subscription', function ($q) use ($year, $month) {
                $q->whereyear('end_at','=',$year);
                $q->whereMonth('end_at','=',$month);
                $q->where('status', 'active');
                $q->where('type', 'restaurant');
            })
            ->count();
        $restaurants_not_renewed = Restaurant::with('subscription')
            ->whereHas('subscription', function ($q) use ($year, $month) {
                $q->whereyear('end_at','=',$year);
                $q->whereMonth('end_at','=',$month);
                $q->where('status', 'finished');
                $q->where('type', 'restaurant');
            })
            ->count();
        // services
        $registered_services = Report::whereType('service')
            ->where('status', 'subscribed')
            ->whereyear('created_at','=',$year)
            ->whereMonth('created_at','=',$month)
            ->count();
        $renew_services = Report::whereType('service')
            ->where('status', 'renewed')
            ->whereyear('created_at','=',$year)
            ->whereMonth('created_at','=',$month)
            ->count();
        $required_renew_services = ServiceSubscription::whereyear('end_at','=',$year)
            ->whereMonth('end_at','=',$month)
            ->whereIn('service_id', [1, 4,9,10])
            ->whereStatus('active')
            ->count();
        $services_not_renewed = ServiceSubscription::whereyear('end_at','=',$year)
            ->whereMonth('end_at','=',$month)
            ->whereIn('service_id', [1, 4, 9,10])
            ->whereStatus('finished')
            ->count();
        // branches
        $subscribed_branches = Report::whereType('branch')
            ->whereStatus('subscribed')
            ->whereyear('created_at','=',$year)
            ->whereMonth('created_at','=',$month)
            ->count();
        $branches_renew_subscription = Branch::with('subscription')
            ->whereHas('subscription', function ($q) use ($year, $month) {
                $q->whereyear('end_at','=',$year);
                $q->whereMonth('end_at','=',$month);
                $q->where('status', 'active');
                $q->where('type', 'branch');
            })
            ->count();
        $branches_not_renewed = Branch::with('subscription')
            ->whereHas('subscription', function ($q) use ($year, $month) {
                $q->whereyear('end_at','=',$year);
                $q->whereMonth('end_at','=',$month);
                $q->where('status', 'active');
                $q->where('type', 'finished');
            })
            ->count();
        $renewed_branches = Report::whereType('branch')
            ->whereStatus('renewed')
            ->whereyear('created_at','=',$year)
            ->whereMonth('created_at','=',$month)
            ->count();
        // sum amounts
        $subscription = Report::whereType('restaurant')
            ->whereStatus('subscribed')
            ->whereyear('created_at','=',$year)
            ->whereMonth('created_at','=',$month)
            ->sum('amount');
        $renew = Report::whereType('restaurant')
            ->whereStatus('renewed')
            ->whereyear('created_at','=',$year)
            ->whereMonth('created_at','=',$month)
            ->sum('amount');
        $services_amount = Report::whereType('service')
            ->where('status', 'subscribed')
            ->whereyear('created_at','=',$year)
            ->whereMonth('created_at','=',$month)
            ->sum('amount');
        $services_renew_amount = Report::whereType('service')
            ->where('status', 'renewed')
            ->whereyear('created_at','=',$year)
            ->whereMonth('created_at','=',$month)
            ->sum('amount');
        $subscribed_branches_amount = Report::whereType('branch')
            ->whereStatus('subscribed')
            ->whereyear('created_at','=',$year)
            ->whereMonth('created_at','=',$month)
            ->sum('amount');
        $renewed_branches_amount = Report::whereType('branch')
            ->whereStatus('renewed')
            ->whereyear('created_at','=',$year)
            ->whereMonth('created_at','=',$month)
            ->sum('amount');
        $month_total_amount = $subscription + $renew + $services_amount + $renewed_branches_amount + $subscribed_branches_amount + $services_renew_amount;
        $month_total_taxes = Report::whereyear('created_at','=',$year)
            ->whereMonth('created_at','=',$month)
            ->whereIn('status' , ['subscribed' , 'renewed'])
            ->sum('tax_value');
        return view('admin.reports.index', compact('registered_restaurants','month_total_taxes','branches_not_renewed', 'services_not_renewed','restaurants_not_renewed','registeredNotSubscribed', 'month_total_amount', 'renewed_branches_amount', 'branches_renew_subscription', 'renewed_branches', 'subscribed_branches_amount', 'subscribed_branches', 'need_renew_restaurants', 'required_renew_services', 'services_renew_amount', 'services_amount', 'renew', 'subscription', 'renew_services', 'registered_services', 'subscribed_restaurants', 'renewed_restaurants', 'year', 'month'));
    }

    public function restaurants($year, $month, $type)
    {
        if ($type == 'registered') {
            $restaurants = Restaurant::with('reports')
                ->whereHas('reports', function ($q) use ($year, $month) {
                    $q->whereType('restaurant');
                    $q->whereStatus('registered');
                    $q->whereyear('created_at','=',$year);
                    $q->whereMonth('created_at','=',$month);
                })
                ->get();
        } elseif ($type == 'subscribed') {
            $restaurants = Restaurant::with('reports')
                ->whereHas('reports', function ($q) use ($year, $month) {
                    $q->whereType('restaurant');
                    $q->whereStatus('subscribed');
                    $q->whereyear('created_at','=',$year);
                    $q->whereMonth('created_at','=',$month);
                })
                ->get();
        } elseif ($type == 'end') {
            $restaurants = Restaurant::with('subscription')
                ->whereHas('subscription', function ($q) use ($year, $month) {
                    $q->whereyear('end_at','=',$year);
                    $q->whereMonth('end_at','=',$month);
                    $q->where('status', 'active');
                    $q->where('type', 'restaurant');
                })
                ->get();
        } elseif ($type == 'renewed') {
            $restaurants = Restaurant::with('reports')
                ->whereHas('reports', function ($q) use ($year, $month) {
                    $q->whereType('restaurant');
                    $q->whereStatus('renewed');
                    $q->whereyear('created_at','=',$year);
                    $q->whereMonth('created_at','=',$month);
                })
                ->get();
        } elseif ($type == 'notSubscribed') {
            $restaurants = Restaurant::with('subscription')
                ->whereHas('subscription', function ($e) {
                    $e->whereIn('status', ['tentative', 'tentative_finished']);
                })
                ->whereyear('created_at','=',$year)
                ->whereMonth('created_at','=',$month)
                ->orWhereNotIn('id', function ($q) {
                    $q->select('restaurant_id')->from('subscriptions');
                })
                ->whereType('restaurant')
                ->whereNotIn('status' , ['inComplete'])
                ->whereyear('created_at','=',$year)
                ->whereMonth('created_at','=',$month)
                ->get();
        }elseif ($type == 'finished')
        {
            $restaurants = Restaurant::with('subscription')
                ->whereHas('subscription', function ($q) use ($year, $month) {
                    $q->whereyear('end_at','=',$year);
                    $q->whereMonth('end_at','=',$month);
                    $q->where('status', 'finished');
                    $q->where('type', 'restaurant');
                })
                ->get();
        }
        $country = Country::first();
        return view('admin.countries.restaurants', compact('country', 'restaurants'));
    }

    public function services($year, $month, $type)
    {
        if ($type == 'sold') {
            $services = ServiceSubscription::with('reports')
                ->whereHas('reports', function ($q) use ($year, $month) {
                    $q->whereType('service');
                    $q->whereStatus('subscribed');
                    $q->whereyear('created_at','=',$year);
                    $q->whereMonth('created_at','=',$month);
                })
                ->get();
        } elseif ($type == 'renew') {
            $services = ServiceSubscription::with('reports')
                ->whereHas('reports', function ($q) use ($year, $month) {
                    $q->whereType('service');
                    $q->whereStatus('renewed');
                    $q->whereyear('created_at','=',$year);
                    $q->whereMonth('created_at','=',$month);
                })
                ->get();
        } elseif ($type == 'end') {
            $services = ServiceSubscription::whereyear('end_at','=',$year)
                ->whereMonth('end_at','=',$month)
                ->whereIn('service_id', [1, 4, 9,10])
                ->whereStatus('active')
                ->get();
        }elseif ($type == 'finished')
        {
            $services = ServiceSubscription::whereyear('end_at','=',$year)
                ->whereMonth('end_at','=',$month)
                ->whereIn('service_id', [1, 4,9,10])
                ->whereStatus('finished')
                ->get();
        }
        return view('admin.reports.services', compact('services', 'year', 'month', 'type'));
    }

    public function branches($year, $month, $type)
    {
        if ($type == 'subscribed') {
            $branches = Branch::with('reports')
                ->whereHas('reports', function ($q) use ($year, $month) {
                    $q->whereType('branch');
                    $q->whereStatus('subscribed');
                    $q->whereyear('created_at','=',$year);
                    $q->whereMonth('created_at','=',$month);
                })
                ->get();
        } elseif ($type == 'renewed') {
            $branches = Branch::with('reports')
                ->whereHas('reports', function ($q) use ($year, $month) {
                    $q->whereType('branch');
                    $q->whereStatus('renewed');
                    $q->whereyear('created_at','=',$year);
                    $q->whereMonth('created_at','=',$month);
                })
                ->get();
        } elseif ($type == 'required_renew') {
            $branches = Branch::with('subscription')
                ->whereHas('subscription', function ($q) use ($year, $month) {
                    $q->whereyear('end_at','=',$year);
                    $q->whereMonth('end_at','=',$month);
                    $q->where('status', 'active');
                    $q->where('type', 'branch');
                })
                ->get();
        }elseif ($type == 'not_renew')
        {
            $branches = Branch::with('subscription')
                ->whereHas('subscription', function ($q) use ($year, $month) {
                    $q->whereyear('end_at','=',$year);
                    $q->whereMonth('end_at','=',$month);
                    $q->where('status', 'finished');
                    $q->where('type', 'branch');
                })
                ->get();
        }
        return view('admin.reports.branches', compact('branches', 'year', 'month', 'type'));
    }

    /**
     * @get countries and cities reports
     *
     */
    public function city_reports()
    {
        $countries = Country::all();
        return view('admin.reports.countries' , compact('countries'));
    }
    public function countries_cities($id)
    {
        $country = Country::find($id);
        $cities = City::whereCountryId($id)->get();
        return view('admin.reports.countries_cities' , compact('cities' , 'country'));
    }
    public function CityRestaurants($id , $status)
    {
        $city = City::find($id);
        if ($status == 'active')
        {
            $restaurants = Restaurant::whereCityId($city->id)
                ->whereStatus('active')
                ->whereType('restaurant')
                ->get();
        }else{
            $restaurants = Restaurant::whereCityId($city->id)
                ->where('status' , '!=' , 'active')
                ->whereType('restaurant')
                ->get();
        }
        return view('admin.reports.restaurants' , compact('city' ,'status', 'restaurants'));
    }

    /**
     * @get @category restaurants
     */
    public function category_reports()
    {
        $categories  = Category::all();
        return view('admin.reports.categories.index' , compact('categories'));
    }
    public function category_restaurants($id)
    {
        $category = Category::find($id);
        $restaurants = $category->restaurant_categories;
        return view('admin.reports.categories.restaurants' , compact('category' , 'restaurants'));
    }
}
