<style>
    /* Premium Flash Deals Section */
    .premium-flash-deals {
        padding: 60px 0;
        background: linear-gradient(135deg, rgba(236, 72, 153, 0.03) 0%, rgba(139, 92, 246, 0.05) 50%, rgba(236, 72, 153, 0.03) 100%);
        position: relative;
        overflow: hidden;
    }
    .premium-flash-deals::before {
        content: '';
        position: absolute;
        top: -100px;
        right: -100px;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(139, 92, 246, 0.1) 0%, transparent 70%);
        border-radius: 50%;
    }
    .premium-flash-deals::after {
        content: '';
        position: absolute;
        bottom: -100px;
        left: -100px;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(236, 72, 153, 0.1) 0%, transparent 70%);
        border-radius: 50%;
    }
    .premium-flash-header {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 30px;
        position: relative;
        z-index: 1;
    }
    .premium-flash-title-wrap {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .premium-flash-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #EC4899 0%, #8B5CF6 100%);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: pulse-glow 2s infinite;
    }
    .premium-flash-icon i {
        font-size: 28px;
        color: #fff;
    }
    .premium-flash-title {
        font-size: 28px;
        font-weight: 800;
        background: linear-gradient(135deg, #EC4899 0%, #8B5CF6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0;
    }
    .premium-countdown-wrapper {
        background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
        border-radius: 20px;
        padding: 15px 30px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: 0 10px 40px rgba(139, 92, 246, 0.3);
    }
    .premium-countdown {
        display: flex;
        gap: 12px;
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .premium-countdown li {
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 10px 16px;
        text-align: center;
        min-width: 65px;
    }
    .premium-countdown li h6 {
        font-size: 24px;
        font-weight: 800;
        color: #fff;
        margin: 0;
        line-height: 1;
    }
    .premium-countdown li span {
        font-size: 11px;
        color: rgba(255,255,255,0.8);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .premium-countdown-text {
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        max-width: 150px;
        text-align: center;
    }
    .premium-flash-nav {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .premium-flash-nav .premium-nav-btn {
        width: 48px;
        height: 48px;
        background: var(--premium-card, #1E293B);
        border: 1px solid var(--premium-border, rgba(255,255,255,0.1));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--premium-text, #F1F5F9);
        font-size: 18px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    body[theme="light"] .premium-flash-nav .premium-nav-btn {
        background: #fff;
        border-color: rgba(0,0,0,0.1);
        color: #1E293B;
    }
    .premium-flash-nav .premium-nav-btn:hover {
        background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
        border-color: transparent;
        color: #fff;
        transform: scale(1.1);
    }
    .premium-see-all {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 700;
        font-size: 15px;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .premium-see-all i {
        background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        transition: transform 0.3s ease;
    }
    .premium-see-all:hover i {
        transform: translateX(5px);
    }
    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 0 20px rgba(236, 72, 153, 0.4); }
        50% { box-shadow: 0 0 40px rgba(236, 72, 153, 0.7); }
    }
    @media (max-width: 992px) {
        .premium-flash-header {
            flex-direction: column;
            text-align: center;
        }
        .premium-flash-title-wrap {
            justify-content: center;
        }
        .premium-countdown-wrapper {
            flex-direction: column;
            padding: 20px;
        }
        .premium-countdown li {
            padding: 8px 12px;
            min-width: 55px;
        }
        .premium-countdown li h6 {
            font-size: 20px;
        }
    }
    @media (max-width: 576px) {
        .premium-flash-deals {
            padding: 40px 0;
        }
        .premium-flash-title {
            font-size: 22px;
        }
        .premium-flash-icon {
            width: 48px;
            height: 48px;
        }
        .premium-flash-icon i {
            font-size: 22px;
        }
        .premium-countdown li {
            padding: 6px 10px;
            min-width: 48px;
        }
        .premium-countdown li h6 {
            font-size: 16px;
        }
    }
</style>

<section class="premium-flash-deals section-gap pb-0">
    <div class="container">
        <div class="premium-flash-header">
            <div class="premium-flash-title-wrap">
                <div class="premium-flash-icon">
                    <i class="bi bi-lightning-charge-fill"></i>
                </div>
                <h2 class="premium-flash-title">{{ translate('flash_deals') }}</h2>
            </div>
            
            <div class="premium-countdown-wrapper">
                <ul class="premium-countdown countdown" data-countdown="{{ $flashDeal['flashDeal']['end_date']->format('m/d/Y').' 11:59:00 PM' ?? ''}}">
                    <li>
                        <h6 class="days">00</h6>
                        <span class="days_text">{{ translate('days') }}</span>
                    </li>
                    <li>
                        <h6 class="hours">00</h6>
                        <span class="hours_text">{{ translate('hours') }}</span>
                    </li>
                    <li>
                        <h6 class="minutes">00</h6>
                        <span class="minutes_text">{{ translate('mins') }}</span>
                    </li>
                    <li>
                        <h6 class="seconds">00</h6>
                        <span class="seconds_text">{{ translate('secs') }}</span>
                    </li>
                </ul>
                <p class="premium-countdown-text mb-0">{{ translate('Limited-Time_Offer_on_Everything') }}</p>
            </div>

            <div class="premium-flash-nav">
                @if(count($flashDeal['flashDealProducts']) > 0)
                    <div class="premium-nav-btn flash-prev">
                        <i class="bi bi-chevron-left"></i>
                    </div>
                    <div class="premium-nav-btn flash-next">
                        <i class="bi bi-chevron-right"></i>
                    </div>
                @endif
                <a href="{{route('flash-deals',[ 'id' => $flashDeal['flashDeal']['id']])}}" class="premium-see-all">
                    {{ translate('See_all') }} <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="overflow-hidden">
            <div class="recommended-slider-wrapper">
                <div class="flash-deal-slider owl-theme owl-carousel">
                    @foreach($flashDeal['flashDealProducts'] as $key => $flashDealProduct)
                        @include('theme-views.partials._product-medium-card',['product' => $flashDealProduct])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
