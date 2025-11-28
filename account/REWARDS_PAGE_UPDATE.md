# Ranks & Rewards Page Modernization Status

## File: `user/rewards.blade.php`

### Status: ⏳ READY FOR UPDATE

This page requires comprehensive modernization to match the dashboard design.

## Required Updates:

### 1. **Add Theme Includes** (Top of file)
```blade
@include($activeTemplate . 'css.modern-finance-theme')
@include($activeTemplate . 'css.mobile-fixes')
@include($activeTemplate . 'partials.theme-switcher')
```

### 2. **Add Page Header**
```blade
<div class="dashboard-header mb-4">
    <h2 class="page-title"><i class="bi bi-trophy-fill"></i> @lang('Ranks & Rewards')</h2>
    <p class="page-subtitle">@lang('Track your progress and rewards')</p>
</div>
```

### 3. **Convert Top 3 Cards to Gradient Cards**
- Rank Status → gradient-card-1
- Rewards Summary → gradient-card-2  
- BBS Account → gradient-card-3

### 4. **Modernize Tabs**
```blade
<ul class="nav nav-tabs modern-tabs">
  <li class="nav-item">
    <button class="nav-link active">
      <i class="bi bi-award-fill"></i> @lang('All Ranks')
    </button>
  </li>
  <li class="nav-item">
    <button class="nav-link">
      <i class="bi bi-clock-history"></i> @lang('Rewards History')
    </button>
  </li>
</ul>
```

### 5. **Update Rank Cards**
- Add dashboard-item class
- Enhance progress bars with gradients
- Add hover effects
- Update icons to Bootstrap Icons

### 6. **Modernize History Table**
- Add purple gradient header
- Card-style rows
- Enhance badges
- Add icons to columns

### 7. **Add Mobile Bottom Nav**
```blade
@include($activeTemplate . 'partials.mobile-bottom-nav')
```

### 8. **Add Icon Enhancer**
```blade
@push('script')
@include($activeTemplate . 'js.icon-enhancer')
@endpush
```

## Icons Needed:
- 🏆 `bi-trophy-fill` - Page title
- 👑 `bi-award-fill` - All Ranks tab
- 🕐 `bi-clock-history` - History tab
- 📊 `bi-bar-chart-fill` - Rank status
- 🎁 `bi-gift-fill` - Rewards
- 🔒 `bi-lock-fill` - BBS locked
- ✅ `bi-check-circle-fill` - Delivered
- ⏳ `bi-hourglass-split` - Pending

## Priority: HIGH
This page is user-facing and needs the modern treatment.

## Estimated Changes:
- ~400+ lines to add
- Modern gradient cards
- Enhanced tabs
- Beautiful table
- Progress bar improvements
