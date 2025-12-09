<style>
    /* Premium How-To Section */
    .premium-features-section {
        padding: 60px 0;
        background: var(--premium-card, #1E293B);
        border-radius: 30px;
        margin: 40px 0;
        border: 1px solid var(--premium-border, rgba(255,255,255,0.08));
        position: relative;
        overflow: hidden;
    }
    body[theme="light"] .premium-features-section {
        background: #ffffff;
        border-color: rgba(0,0,0,0.08);
    }
    .premium-features-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #8B5CF6, #EC4899, #10B981, #F59E0B);
    }
    .premium-features-header {
        text-align: center;
        margin-bottom: 50px;
    }
    .premium-features-title {
        font-size: 32px;
        font-weight: 800;
        background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 15px;
    }
    .premium-features-subtitle {
        font-size: 16px;
        color: var(--premium-muted, #94A3B8);
        max-width: 600px;
        margin: 0 auto;
    }
    body[theme="light"] .premium-features-subtitle {
        color: #64748B;
    }
    .premium-features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
    }
    .premium-feature-card {
        position: relative;
        padding: 30px;
        background: rgba(139, 92, 246, 0.05);
        border: 1px solid var(--premium-border, rgba(255,255,255,0.1));
        border-radius: 24px;
        transition: all 0.4s ease;
        text-align: center;
    }
    body[theme="light"] .premium-feature-card {
        background: rgba(139, 92, 246, 0.03);
        border-color: rgba(0,0,0,0.08);
    }
    .premium-feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 50px rgba(139, 92, 246, 0.2);
        border-color: #8B5CF6;
    }
    .premium-feature-number {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        font-weight: 800;
        color: #fff;
        margin: 0 auto 20px;
        box-shadow: 0 10px 30px rgba(139, 92, 246, 0.3);
    }
    .premium-feature-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--premium-text, #F1F5F9);
        margin-bottom: 10px;
    }
    body[theme="light"] .premium-feature-title {
        color: #1E293B;
    }
    .premium-feature-desc {
        font-size: 14px;
        color: var(--premium-muted, #94A3B8);
        line-height: 1.6;
    }
    body[theme="light"] .premium-feature-desc {
        color: #64748B;
    }

    /* Premium Support Section */
    .premium-support-section {
        padding: 60px 0;
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.03) 0%, rgba(139, 92, 246, 0.03) 100%);
    }
    .premium-support-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
    }
    .premium-support-card {
        background: var(--premium-card, #1E293B);
        border: 1px solid var(--premium-border, rgba(255,255,255,0.08));
        border-radius: 24px;
        padding: 30px;
        text-align: center;
        transition: all 0.4s ease;
    }
    body[theme="light"] .premium-support-card {
        background: #ffffff;
        border-color: rgba(0,0,0,0.08);
    }
    .premium-support-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 50px rgba(16, 185, 129, 0.15);
        border-color: #10B981;
    }
    .premium-support-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
    }
    .premium-support-icon img {
        width: 40px;
        height: 40px;
        object-fit: contain;
        filter: brightness(0) invert(1);
    }
    .premium-support-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--premium-text, #F1F5F9);
        margin-bottom: 10px;
    }
    body[theme="light"] .premium-support-title {
        color: #1E293B;
    }
    .premium-support-desc {
        font-size: 14px;
        color: var(--premium-muted, #94A3B8);
        line-height: 1.6;
    }
    body[theme="light"] .premium-support-desc {
        color: #64748B;
    }
    @media (max-width: 768px) {
        .premium-features-section {
            padding: 40px 20px;
            margin: 20px 0;
            border-radius: 20px;
        }
        .premium-features-title {
            font-size: 24px;
        }
        .premium-feature-card {
            padding: 25px;
        }
        .premium-feature-number {
            width: 60px;
            height: 60px;
            font-size: 24px;
        }
    }
</style>

<section class="pt-4">
    <div class="container">
        @if (!empty($web_config['features_section']['features_section_top']) || !empty($web_config['features_section']['features_section_middle']))
        <div class="premium-features-section">
            @if (!empty($web_config['features_section']['features_section_top']))
                <div class="premium-features-header">
                    @php($featuresSectionTop = $web_config['features_section']['features_section_top'])
                    <h2 class="premium-features-title">
                        {{ $featuresSectionTop['title'] ?? '' }}
                    </h2>
                    <p class="premium-features-subtitle">
                        {{ $featuresSectionTop['subtitle'] ?? '' }}
                    </p>
                </div>
            @endif

            @if (!empty($web_config['features_section']['features_section_middle']))
                @php($totalFeatures = count($web_config['features_section']['features_section_middle']))
                @if($totalFeatures > 0)
                <div class="premium-features-grid">
                    @foreach ($web_config['features_section']['features_section_middle'] as $key=> $item)
                        <div class="premium-feature-card">
                            <div class="premium-feature-number">
                                {{ ($key + 1 < 10 ? '0' : '') }}{{ $key + 1 }}
                            </div>
                            <h5 class="premium-feature-title">{{ $item['title'] ?? '' }}</h5>
                            <p class="premium-feature-desc">{{ $item['subtitle'] ?? '' }}</p>
                        </div>
                    @endforeach
                </div>
                @endif
            @endif
        </div>
        @endif
    </div>
</section>

@if(!empty($web_config['features_section']['features_section_bottom']))
    @if(count($web_config['features_section']['features_section_bottom']) > 0)
    <section class="premium-support-section">
        <div class="container">
            <div class="premium-support-grid">
                @foreach($web_config['features_section']['features_section_bottom'] as $item)
                    <div class="premium-support-card">
                        <div class="premium-support-icon">
                            <?php
                                $imageName = isset($item['icon']['image_name']) ? $item['icon']['image_name'] : ($item['icon'] ?? '');
                                $storageType = isset($item['icon']['storage']) ? $item['icon']['storage'] : 'public';
                                $imagePath = storageLink('banner', $imageName, $storageType);
                            ?>
                            <img loading="lazy" src="{{ getStorageImages(path: $imagePath, type:'banner') }}"
                                 alt="{{ translate('banner') }}">
                        </div>
                        <h6 class="premium-support-title">{{ $item['title'] ?? '' }}</h6>
                        <p class="premium-support-desc">{{ $item['subtitle'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @else
    <div class="pt-4"></div>
    @endif
@endif
