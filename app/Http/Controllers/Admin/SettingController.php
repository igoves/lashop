<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateProfileRequest;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class SettingController extends AdminController
{
    private const GROUPS = [
        'general' => [
            'label' => 'General',
            'slugs' => ['site_name', 'text_on_home', 'text_on_footer'],
        ],
        'catalog' => [
            'label' => 'Catalog',
            'slugs' => ['products_count', 'search_count', 'rate'],
        ],
        'contacts' => [
            'label' => 'Contacts',
            'slugs' => ['tel_1', 'tel_2', 'email', 'address'],
        ],
        'order' => [
            'label' => 'Order',
            'slugs' => ['order_statuses', 'delivery_methods', 'payment_methods'],
        ],
        'images' => [
            'label' => 'Image Sizes',
            'slugs' => ['image_size_big', 'image_size_medium', 'image_size_small'],
        ],
    ];

    private const TITLES = [
        'site_name' => 'Site Name',
        'text_on_home' => 'Text on Home',
        'text_on_footer' => 'Text on Footer',
        'products_count' => 'Products per Page',
        'search_count' => 'Search Results per Page',
        'rate' => 'Price Coefficient',
        'tel_1' => 'Phone 1',
        'tel_2' => 'Phone 2',
        'email' => 'Contact Email',
        'address' => 'Contact Address',
        'order_statuses' => 'Order Statuses',
        'delivery_methods' => 'Delivery Methods',
        'payment_methods' => 'Payment Methods',
        'image_size_big' => 'Big (WxH)',
        'image_size_medium' => 'Medium (WxH)',
        'image_size_small' => 'Small (WxH)',
    ];

    private const DEFAULTS = [
        'site_name' => 'Lashop',
        'text_on_home' => 'Welcome to our online store! We offer a wide range of quality products at competitive prices. Free shipping on orders over $50.',
        'text_on_footer' => 'Your trusted online store for quality products. We ship worldwide and offer easy returns.',
        'products_count' => '6',
        'search_count' => '6',
        'rate' => '1',
        'tel_1' => '+1 (555) 123-4567',
        'tel_2' => '+1 (555) 987-6543',
        'email' => 'info@lashop.com',
        'address' => '123 Commerce Street, New York, NY 10001',
        'order_statuses' => "New\nIn Progress\nDone",
        'delivery_methods' => "Pickup\nCourier\nPost",
        'payment_methods' => "Cash on delivery\nBank transfer\nCard",
        'image_size_big' => '800x600',
        'image_size_medium' => '400x300',
        'image_size_small' => '120x90',
    ];

    private const TEXTAREA_FIELDS = [
        'text_on_home', 'text_on_footer',
        'order_statuses', 'delivery_methods', 'payment_methods',
    ];

    public function index(): View
    {
        $all = Setting::query()->pluck('value', 'slug')->toArray();

        $groups = [];
        foreach (self::GROUPS as $key => $group) {
            $settings = [];
            foreach ($group['slugs'] as $slug) {
                $settings[$slug] = [
                    'title' => self::TITLES[$slug] ?? $slug,
                    'value' => $all[$slug] ?? self::DEFAULTS[$slug] ?? '',
                ];
            }
            $groups[$key] = [
                'label' => $group['label'],
                'settings' => $settings,
            ];
        }

        $activeTab = request('tab', array_key_first($groups));

        return view('admin.settings.index', compact('groups', 'activeTab'));
    }

    public function bulkUpdate(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'settings' => ['required', 'array'],
            'settings.*' => ['nullable', 'string', 'max:10000'],
        ]);

        foreach ($data['settings'] as $slug => $value) {
            Setting::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => self::TITLES[$slug] ?? $slug,
                    'value' => $value !== '' ? $value : null,
                ]
            );
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated.');
    }

    public function edit(Setting $setting): View
    {
        return view('admin.settings.edit', compact('setting'));
    }

    public function update(Request $request, Setting $setting): RedirectResponse
    {
        $validated = $request->validate([
            'value' => ['nullable', 'string', 'max:10000'],
        ]);

        $setting->update(['value' => $validated['value'] ?? null]);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Setting updated.');
    }

    public function profile(Request $request): View
    {
        $user = $request->user();

        return view('admin.settings.profile', compact('user'));
    }

    public function updateProfile(UpdateProfileRequest $request): RedirectResponse
    {
        $user = $request->user();

        if (! Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->email = $request->input('email');
        $user->save();

        if ($request->filled('password')) {
            $user->forceFill(['password' => $request->input('password')])->save();
        }

        return redirect()->route('admin.settings.profile')
            ->with('success', 'Profile updated.');
    }
}
