(function(){
    const $btn = document.getElementById('ai-generate-btn');
    if(!$btn) return;

    const $input = document.getElementById('ai-images-input');
    const $bar = document.getElementById('ai-progress');
    const $status = document.getElementById('ai-status');
    const $errors = document.querySelector('#ai-errors .ai-error-text');
    const $errorsWrap = document.getElementById('ai-errors');
    const $thumb = document.getElementById('ai-thumb-preview');
    const $previewWrap = document.getElementById('ai-preview');
    const $additional = document.getElementById('ai-additional-preview');
    const $progressCard = document.getElementById('ai-progress-card');
    const $uploadZone = document.getElementById('ai-upload-zone');
    const $uploadPreview = document.getElementById('ai-upload-preview');
    const $modelSelector = document.getElementById('ai-model-selector');
    const $useAllModels = document.getElementById('ai-use-all-models');
    const $activeModelsDisplay = document.getElementById('ai-active-models');

    // Magic effect helper
    function addMagicEffect(element) {
        if (!element) return;
        element.classList.add('ai-magic-field');
        setTimeout(() => {
            element.classList.remove('ai-magic-field');
        }, 4500); // 1.5s * 3 iterations
    }

    // Drag & Drop for upload zone
    if ($uploadZone && $input) {
        $uploadZone.addEventListener('click', () => $input.click());
        
        $uploadZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            $uploadZone.classList.add('dragover');
        });
        
        $uploadZone.addEventListener('dragleave', () => {
            $uploadZone.classList.remove('dragover');
        });
        
        $uploadZone.addEventListener('drop', (e) => {
            e.preventDefault();
            $uploadZone.classList.remove('dragover');
            if (e.dataTransfer.files.length) {
                $input.files = e.dataTransfer.files;
                $input.dispatchEvent(new Event('change'));
            }
        });
    }

    // Model selection handler
    if ($modelSelector) {
        $modelSelector.addEventListener('change', function() {
            updateActiveModels();
        });
    }

    if ($useAllModels) {
        $useAllModels.addEventListener('change', function() {
            if (this.checked) {
                $modelSelector.disabled = true;
                $modelSelector.style.opacity = '0.5';
            } else {
                $modelSelector.disabled = false;
                $modelSelector.style.opacity = '1';
            }
            updateActiveModels();
        });
    }

    function updateActiveModels() {
        if (!$activeModelsDisplay) return;
        
        const allModels = {
            'gemini-2.5-flash': 'Gemini 2.5 Flash',
            'gemini-2.5-flash-image-preview': 'Gemini 2.5 Flash Image Preview',
            'gemini-2.5-flash-image': 'Gemini 2.5 Flash Image',
            'gemini-2.5-flash-preview-09-2025': 'Gemini 2.5 Flash Preview (Sep 2025)',
            'gemini-2.5-pro': 'Gemini 2.5 Pro'
        };
        
        let html = '';
        if ($useAllModels && $useAllModels.checked) {
            // Show all models
            Object.values(allModels).forEach(name => {
                html += `<span class="ai-model-tag"><i class="fi fi-rr-check-circle"></i> ${name}</span>`;
            });
        } else {
            // Show selected model
            const selectedValue = $modelSelector.value;
            const selectedName = allModels[selectedValue] || 'Unknown Model';
            html = `<span class="ai-model-tag"><i class="fi fi-rr-check-circle"></i> ${selectedName}</span>`;
        }
        
        $activeModelsDisplay.innerHTML = html;
    }

    // Preview selected images in the AI sidebar immediately
    if($input){
        $input.addEventListener('change', function(){
            const files = Array.from($input.files || []);
            if(!files.length) {
                $uploadPreview.innerHTML = '';
                return;
            }
            
            // Show preview thumbnails in upload zone
            $uploadPreview.innerHTML = files.map(f => {
                try {
                    const url = URL.createObjectURL(f);
                    return `<img src="${url}" alt="${f.name}">`;
                } catch(_) {
                    return '';
                }
            }).join('');
            
            // Show in preview card
            try{
                const firstUrl = URL.createObjectURL(files[0]);
                $thumb.src = firstUrl;
                $previewWrap.style.display = '';
                $additional.innerHTML = files.slice(1).map(f => {
                    try{ return `<img src="${URL.createObjectURL(f)}" class="rounded border" style="width:72px;height:auto;"/>`; }
                    catch(_){ return ''; }
                }).join('');
            }catch(_){ /* ignore */ }
        });
    }

    const analyzeUrl = document.getElementById('route-admin-products-ai-analyze')?.dataset.url;
    const genUrl = document.getElementById('route-admin-products-ai-generate-images')?.dataset.url;

    function setProgress(p){
        $bar.style.width = p+"%";
        $bar.setAttribute('aria-valuenow', p);
    }

    async function fallbackFlow(reason){
        logLine(`Falling back without AI: ${reason}`, 'error');
        setTask('analyze','error');
        setTask('map','active'); setProgress(60); setStatusText(t('msg-ai-mapping','Mapping AI output'), 60);
        const fb = buildFallbackData($input.files);
        setTask('map','done'); logLine('✔ '+t('msg-ai-mapping','Mapping AI output'),'success');

        setTask('fill','active'); setProgress(65); setStatusText(t('msg-ai-filling','Filling form fields'), 65);
        await fillForm(fb);
        await ensureCategoryChain();
        await ensureBrandUnit();
        ensureSkuAndTags();
        setTask('fill','done'); logLine('✔ '+t('msg-ai-filling','Filling form fields'),'success');

        // Try generating images with fallback prompt; if not ok, attach placeholders
        setTask('generate','active'); setProgress(75); setStatusText(t('msg-ai-generating','Generating images'), 75);
        try{
            const plain = plainTextFromHtml(fb.description || '');
            const prompt = (`${fb.name || ''} product photo, studio, ecommerce, white background. ${plain}`).slice(0, 400);
            const gi = await fetch(genUrl, { method: 'POST', headers: {'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}, body: JSON.stringify({ prompt: prompt, context: {}, count: 8 })});
            if(gi.ok){
                const data = await gi.json();
                if(data.status === 'success'){
                    setTask('generate','done'); logLine('✔ '+t('msg-ai-generating','Generating images'),'success');
                    setTask('attach','active'); setProgress(90); setStatusText(t('msg-ai-attaching','Attaching images'), 90);
                    await attachImages(data.data);
                    insertImagesIntoDescription(fb.name, data.data);
                    setTask('attach','done'); logLine('✔ '+t('msg-ai-attaching','Attaching images'),'success');
                    toast('success', t('msg-ai-images-generated', 'AI images generated'));
                } else {
                    throw new Error(data.message || 'gen failed');
                }
            } else {
                throw new Error('HTTP '+gi.status);
            }
        }catch(e){
            setTask('generate','error');
            logLine('Image generation failed, attaching placeholders', 'error');
            setTask('attach','active'); setProgress(90); setStatusText(t('msg-ai-attaching','Attaching images'), 90);
            await attachPlaceholderImages(4);
            setTask('attach','done');
        }

        setTask('done','done'); setProgress(100); setStatusText(t('msg-ai-completed','All steps completed'), 100);
        if($status){ $status.classList.remove('text-muted'); $status.classList.add('text-success'); }
        logLine(t('msg-ai-completed','All steps completed'), 'success');
        toast('success', t('msg-ai-completed','All steps completed'));
    }

    function toast(type, msg){
        if(window.toastMagic && typeof window.toastMagic[type] === 'function'){
            window.toastMagic[type](msg);
        } else if(window.toastr && typeof window.toastr[type] === 'function'){
            window.toastr[type](msg);
        } else {
            console.log(type.toUpperCase()+':', msg);
        }
    }

    function buildLongDescription(data){
        const name = (data?.name || 'Product').toString();
        const brand = (data?.brand || '').toString();
        const unit = (data?.unit || '').toString();
        const tags = Array.isArray(data?.tags) ? data.tags.slice(0,8) : [];
        const specs = [brand && `Brand: ${brand}`, unit && `Unit: ${unit}`].filter(Boolean);
        const features = tags.length ? tags : ['High quality','Great value','Designed for everyday use'];
        return (
            `<h2>${name} – Detailed Overview</h2>`+
            `<p>Discover the ${name}${brand?` by ${brand}`:''}. Built for reliability and performance, this product offers excellent value for modern shoppers.</p>`+
            `<h3>Key Features</h3>`+
            `<ul>`+features.map(f=>`<li>${f}</li>`).join('')+`</ul>`+
            `<h3>Specifications</h3>`+
            `<ul>`+specs.map(s=>`<li>${s}</li>`).join('')+`</ul>`+
            `<h3>Materials & Dimensions</h3>`+
            `<p>Carefully crafted materials. Dimensions and weight vary by variant. See product options for details.</p>`+
            `<h3>What's in the Box</h3>`+
            `<ul><li>${name}</li><li>User guide</li><li>Warranty information</li></ul>`+
            `<h3>How to Use</h3>`+
            `<p>Unbox, inspect for any transit issues, and follow the quick-start tips to get the best experience.</p>`+
            `<h3>Care & Maintenance</h3>`+
            `<p>Keep away from extreme temperatures and clean with a soft, dry cloth. Refer to the user guide for more.</p>`+
            `<h3>Warranty & Support</h3>`+
            `<p>Backed by our standard warranty. For assistance, contact support.</p>`+
            `<h3>FAQs</h3>`+
            `<ul><li><strong>Is it ready to use?</strong> Yes, setup is quick and simple.</li><li><strong>Is it durable?</strong> Built for daily reliability.</li></ul>`+
            `<p><strong>Order the ${name} today</strong> and enjoy quality you can trust.</p>`
        );
    }

    function insertImagesIntoDescription(productName, imagesObj){
        try{
            const defaultLang = getActiveLang();
            const q = document.querySelector(`#description-${defaultLang}-editor .ql-editor`);
            if(!q || !imagesObj) return;
            const urls = [];
            if(imagesObj.thumbnail) urls.push(imagesObj.thumbnail);
            if(Array.isArray(imagesObj.additional)) urls.push(...imagesObj.additional);
            if(!urls.length) return;
            let gallery = q.querySelector('.ai-desc-gallery');
            if(!gallery){
                q.insertAdjacentHTML('beforeend', `<h3>Product Gallery</h3><div class="ai-desc-gallery"></div>`);
                gallery = q.querySelector('.ai-desc-gallery');
            }
            gallery.innerHTML = urls.map(u=>`<p><img src="${u}" alt="${(productName||'Product')} image"></p>`).join('');
            const ta = document.getElementById(`description-${defaultLang}`);
            if(ta) ta.value = q.innerHTML;
            logLine(`Inserted ${urls.length} images in description`, 'success');
        }catch(e){ console.warn('insertImagesIntoDescription error', e); }
    }
    function t(id, fallback){
        const el = document.getElementById(id);
        return (el && el.dataset && el.dataset.text) ? el.dataset.text : fallback;
    }

    function plainTextFromHtml(html){
        return (html || '').toString().replace(/<[^>]+>/g,' ').replace(/\s+/g,' ').trim();
    }

    function getActiveLang(){
        const nav = document.querySelector('.lang_tab .nav-link.active');
        if(nav && nav.id && nav.id.endsWith('-link')){ return nav.id.replace('-link',''); }
        const pane = document.querySelector('[id$="_form"].show.active');
        if(pane && pane.id && pane.id.endsWith('-form')){ return pane.id.replace('-form',''); }
        return 'en';
    }

    const tasks = {
        validate: 'ai-task-validate',
        analyze: 'ai-task-analyze',
        map: 'ai-task-map',
        fill: 'ai-task-fill',
        generate: 'ai-task-generate',
        attach: 'ai-task-attach',
        done: 'ai-task-done',
    };
    function resetTasks(){
        Object.values(tasks).forEach(id => {
            const li = document.getElementById(id);
            if(!li) return;
            const icon = li.querySelector('.ai-task-icon');
            if(icon) icon.textContent = '○';
            li.classList.remove('text-success','text-danger');
            li.classList.add('text-muted');
        });
    }
    function setTask(taskKey, state){
        const id = tasks[taskKey];
        const li = id ? document.getElementById(id) : null;
        if(!li) return;
        
        // Remove all state classes
        li.classList.remove('pending', 'running', 'success', 'error');
        
        // Add new state class
        if(state === 'done') {
            li.classList.add('success');
        } else if(state === 'error') {
            li.classList.add('error');
        } else if(state === 'active') {
            li.classList.add('running');
        } else {
            li.classList.add('pending');
        }
    }
    function logLine(msg, type){
        // Logs removed from UI, only console logging
        console.log(`[AI] ${type?.toUpperCase() || 'LOG'}: ${msg}`);
    }
    function setStatusText(text, percent){
        $status.innerText = (typeof percent === 'number' ? ('['+percent+'%] ') : '') + text;
    }

    function waitFor(predicate, timeoutMs=3000, interval=150){
        return new Promise(resolve => {
            const start = Date.now();
            const tick = () => {
                try{
                    if(predicate()) return resolve(true);
                }catch(_){}
                if(Date.now() - start >= timeoutMs) return resolve(false);
                setTimeout(tick, interval);
            };
            tick();
        });
    }

    function selectFirstNonPlaceholder(sel){
        if(!sel) return false;
        const opt = Array.from(sel.options).find(o => !o.disabled && o.value !== '' && o.value !== 'null' && o.value !== null);
        if(opt){ sel.value = opt.value; sel.dispatchEvent(new Event('change', { bubbles: true })); return true; }
        return false;
    }

    function buildFallbackData(files){
        const first = files && files[0] ? files[0].name : '';
        const base = first ? first.replace(/\.[^.]+$/, '').replace(/[\-_]+/g,' ').trim() : 'New Product';
        const title = base ? base.replace(/\s+/g,' ').replace(/\b\w/g, c=>c.toUpperCase()) : 'New Product';
        const tags = base ? base.split(' ').filter(Boolean).slice(0,5) : ['product'];
        const sku = 'AI' + Math.random().toString(36).slice(2,10).toUpperCase();
        return {
            name: title,
            description: `Auto-generated description for ${title}.`,
            category: '', sub_category: '', sub_sub_category: '',
            brand: '', product_type: 'physical', sku: sku, unit: 'pc',
            tags: tags,
            meta_title: title,
            meta_description: `Buy ${title} online. Quality product, great value.`,
        };
    }

    async function ensureCategoryChain(){
        const cat = document.querySelector('select[name="category_id"]');
        if(cat && (!cat.value || cat.value === '' || cat.value === 'null')){
            if(selectFirstNonPlaceholder(cat)) logLine('Selected default category', 'success');
        }
        await waitFor(()=> document.getElementById('sub-category-select'), 1500);
        const sub = document.getElementById('sub-category-select');
        if(sub){ await waitFor(()=> sub.options && sub.options.length > 1, 1500); selectFirstNonPlaceholder(sub) && logLine('Selected default sub category','success'); }
        await waitFor(()=> document.getElementById('sub-sub-category-select'), 1500);
        const sub2 = document.getElementById('sub-sub-category-select');
        if(sub2){ await waitFor(()=> sub2.options && sub2.options.length > 1, 1500); selectFirstNonPlaceholder(sub2) && logLine('Selected default sub sub category','success'); }
    }

    async function ensureBrandUnit(){
        const brandSel = document.querySelector('select[name="brand_id"]');
        if(brandSel && (!brandSel.value || brandSel.value === 'null')){
            selectFirstNonPlaceholder(brandSel) && logLine('Selected default brand','success');
        }
        const unitSel = document.querySelector('select[name="unit"]');
        if(unitSel && (!unitSel.value || unitSel.value === 'null')){
            selectFirstNonPlaceholder(unitSel) && logLine('Selected default unit','success');
        }
    }

    function ensureSkuAndTags(){
        const sku = document.getElementById('generate-sku-code');
        if(sku && (!sku.value || sku.value.trim() === '')){
            sku.value = 'AI' + Math.random().toString(36).slice(2,10).toUpperCase();
            logLine('Generated SKU','success');
        }
        const nameInput = document.querySelector('input.product-title-default-language');
        const tagsInput = document.querySelector('input[name="tags"]');
        if(tagsInput && (!tagsInput.value || tagsInput.value.trim() === '')){
            const base = (nameInput?.value || 'product').toLowerCase();
            const tags = Array.from(new Set(base.split(' ').filter(Boolean).slice(0,5)));
            tagsInput.value = tags.join(',');
            tagsInput.dispatchEvent(new Event('change', { bubbles: true }));
            logLine('Generated tags','success');
        }
    }

    function createPlaceholderBlob(width=400, height=500, text='Product Image'){
        const canvas = document.createElement('canvas');
        canvas.width = width; canvas.height = height;
        const ctx = canvas.getContext('2d');
        ctx.fillStyle = '#f3f4f6'; ctx.fillRect(0,0,width,height);
        ctx.fillStyle = '#94a3b8';
        ctx.font = 'bold 20px Arial';
        ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
        ctx.fillText(text, width/2, height/2);
        const b64 = canvas.toDataURL('image/png');
        const bin = atob(b64.split(',')[1]);
        const arr = new Uint8Array(bin.length);
        for(let i=0;i<bin.length;i++){ arr[i] = bin.charCodeAt(i); }
        return new Blob([arr], {type:'image/png'});
    }

    async function attachPlaceholderImages(count=4){
        const thumbInput = document.querySelector('input[name="image"]') || document.querySelector('input.upload-file__input.single_file_input');
        const dt = new DataTransfer();
        const thumbBlob = createPlaceholderBlob(400,500,'Thumbnail');
        if(thumbInput){
            const file = new File([thumbBlob], 'ai-thumb.png', {type:'image/png'});
            dt.items.add(file);
            thumbInput.files = dt.files;
            thumbInput.dispatchEvent(new Event('change', { bubbles: true }));
            $thumb.src = URL.createObjectURL(file); $previewWrap.style.display = '';
            logLine('Placeholder thumbnail attached','success');
        }
        const addPicker = await waitForAdditionalPicker();
        if(addPicker){
            const dt2 = new DataTransfer();
            for(let i=0;i<Math.max(1,count-1);i++){
                const b = createPlaceholderBlob(400,500,`Img ${i+1}`);
                dt2.items.add(new File([b], `ai-${i+1}.png`, {type:'image/png'}));
            }
            addPicker.files = dt2.files;
            addPicker.dispatchEvent(new Event('change', { bubbles: true }));
            $additional.innerHTML = Array.from(dt2.files).map(f => `<img src="${URL.createObjectURL(f)}" class="rounded border" style="width:72px;height:auto;"/>`).join('');
            $previewWrap.style.display = '';
            logLine('Placeholder additional images attached','success');
        }
    }

    async function fetchToBlob(url){
        const res = await fetch(url);
        return await res.blob();
    }

    function forceSinglePreview(input){
        try{
            const card = input.closest('.upload-file');
            const img = card ? card.querySelector('.upload-file-img') : null;
            const textbox = card ? card.querySelector('.upload-file-textbox') : null;
            const overlay = card ? card.querySelector('.overlay') : null;
            if(input.files && input.files[0]){
                const reader = new FileReader();
                reader.onload = (e)=>{
                    if(img){ img.src = e.target.result; img.style.display='block'; }
                    if(textbox){ textbox.style.display='none'; }
                    if(overlay){ overlay.classList.add('show'); }
                    if(card){ card.classList.add('input-disabled'); }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }catch(err){ /* ignore */ }
    }

    async function setFileInputFromUrls(input, urls){
        const dt = new DataTransfer();
        if(!urls || !urls.length) {
            console.warn('setFileInputFromUrls: no URLs provided');
            return false;
        }
        
        console.log('setFileInputFromUrls: fetching', urls.length, 'images');
        const addAll = urls.map(async (u, idx) => {
            try{
                console.log('Fetching image:', u);
                const r = await fetch(u);
                if(!r.ok) throw new Error('HTTP '+r.status);
                const b = await r.blob();
                console.log('Fetched blob:', b.size, 'bytes, type:', b.type);
                const ext = (u.split('.').pop() || 'png').split('?')[0];
                const type = (b.type && b.type.startsWith('image/')) ? b.type : 'image/png';
                const file = new File([b], `ai-${idx}.${ext}`, {type});
                dt.items.add(file);
                console.log('Added file to DataTransfer:', file.name);
            }catch(err){ 
                console.error('Failed to fetch image:', u, err); 
            }
        });
        await Promise.all(addAll);
        
        if(dt.files.length === 0) {
            console.error('setFileInputFromUrls: no files added');
            return false;
        }
        
        console.log('Setting input.files with', dt.files.length, 'files');
        input.files = dt.files;
        input.dispatchEvent(new Event('input', { bubbles: true }));
        input.dispatchEvent(new Event('change', { bubbles: true }));
        forceSinglePreview(input);
        console.log('setFileInputFromUrls: complete');
        return true;
    }

    function waitForAdditionalPicker(timeoutMs = 2000){
        return new Promise(resolve => {
            const start = Date.now();
            const tryFind = () => {
                const pick = document.querySelector('#additional_Image_Section .multi_image_picker')
                    || document.querySelector('.multi_image_picker');
                if(pick){ return resolve(pick); }
                if(Date.now() - start > timeoutMs){ return resolve(null); }
                setTimeout(tryFind, 150);
            };
            tryFind();
        });
    }

    async function attachToSpartan(container, urls){
        if(!container || !urls || !urls.length) return;
        const cont = container;
        for(let i=0;i<urls.length;i++){
            const u = urls[i];
            try{
                const resp = await fetch(u);
                if(!resp.ok) throw new Error('HTTP '+resp.status);
                const b = await resp.blob();
                const ext = (u.split('.').pop() || 'png').split('?')[0];
                const file = new File([b], `ai-${i}.${ext}`, {type: b.type || 'image/png'});
                const dt = new DataTransfer();
                dt.items.add(file);
                // pick the last spartan input (new empty slot)
                const inputs = cont.querySelectorAll('.spartan_image_input');
                const input = inputs[inputs.length - 1];
                if(!input) continue;
                const beforeCount = inputs.length;
                input.files = dt.files;
                input.dispatchEvent(new Event('change', { bubbles: true }));
                // wait for new row to be added or image rendered
                await waitFor(() => cont.querySelectorAll('.spartan_image_input').length > beforeCount
                    || cont.querySelector(`img.img_[data-spartanindeximage="${input.getAttribute('data-spartanindexinput')}"]`), 1200);
            }catch(e){ console.warn('attachToSpartan failed for', u, e); }
        }
    }

    async function fillForm(data){
        try{
            let changed = 0;
            // Name (default language)
            const nameInput = document.querySelector('input.product-title-default-language');
            if(nameInput && data.name){ 
                nameInput.value = data.name; 
                changed++; 
                addMagicEffect(nameInput);
            }

            // Description via Quill
            const defaultLang = getActiveLang();
            const q = document.querySelector(`#description-${defaultLang}-editor .ql-editor`);
            if(q && data.description){
                q.innerHTML = data.description; changed++;
                // Ensure it is long-form SEO if too short
                const plainLen = data.description.replace(/<[^>]+>/g,' ').replace(/\s+/g,' ').trim().length;
                if(plainLen < 400){
                    const longHtml = buildLongDescription(data);
                    if(longHtml){ q.innerHTML = longHtml; changed++; logLine('Expanded description for SEO', 'success'); }
                }
                addMagicEffect(q.closest('.ql-container'));
            }
            const ta = document.getElementById(`description-${defaultLang}`);
            if(ta && q){ ta.value = q.innerHTML; }

            // Category selects (best-effort by matching text)
            function selectByText(sel, text){
                if(!sel || !text) return;
                const opt = Array.from(sel.options).find(o => o.text?.trim().toLowerCase() === text.trim().toLowerCase());
                if(opt){ sel.value = opt.value; sel.dispatchEvent(new Event('change', { bubbles: true })); changed++; }
            }
            function selectByTextFuzzy(sel, text){
                if(!sel || !text) return false;
                const t = text.trim().toLowerCase();
                let opt = Array.from(sel.options).find(o => o.text?.trim().toLowerCase() === t);
                if(!opt){ opt = Array.from(sel.options).find(o => o.text?.trim().toLowerCase().includes(t)); }
                if(!opt){
                    // try splitting, use first word
                    const first = t.split(' ')[0];
                    opt = Array.from(sel.options).find(o => o.text?.trim().toLowerCase().includes(first));
                }
                if(opt){ 
                    sel.value = opt.value; 
                    sel.dispatchEvent(new Event('change', { bubbles: true })); 
                    changed++; 
                    addMagicEffect(sel);
                    logLine(`Selected ${sel.name || 'field'}: ${opt.text}`, 'success');
                    return true;
                }
                return false;
            }
            const cat = document.querySelector('select[name="category_id"]');
            if(cat && data.category){
                selectByTextFuzzy(cat, data.category);
                setTimeout(()=>{
                    const sub = document.getElementById('sub-category-select');
                    if(sub && data.sub_category){
                        selectByTextFuzzy(sub, data.sub_category);
                        setTimeout(()=>{
                            const sub2 = document.getElementById('sub-sub-category-select');
                            if(sub2 && data.sub_sub_category){
                                selectByTextFuzzy(sub2, data.sub_sub_category);
                            }
                        }, 600);
                    }
                }, 600);
            }

            const brandSel = document.querySelector('select[name="brand_id"]');
            if(brandSel && data.brand){
                selectByTextFuzzy(brandSel, data.brand);
            }

            const sku = document.getElementById('generate-sku-code');
            if(sku && data.sku){ 
                sku.value = data.sku; 
                changed++; 
                addMagicEffect(sku);
            }

            const unitSel = document.querySelector('select[name="unit"]');
            // unit synonyms mapping
            const unitMap = { piece:'pc', pcs:'pc', pieces:'pc', kilogram:'kg', kilograms:'kg', gram:'g', grams:'g', litre:'l', liters:'l', meter:'m', metre:'m' };
            const unitText = (data.unit || '').toString().toLowerCase();
            const normalized = unitMap[unitText] || unitText;
            selectByTextFuzzy(unitSel, normalized);

            const tagsInput = document.querySelector('input[name="tags"]');
            if(tagsInput && data.tags && data.tags.length){
                tagsInput.value = data.tags.join(',');
                tagsInput.dispatchEvent(new Event('change', { bubbles: true }));
                changed++;
                addMagicEffect(tagsInput);
            }

            // Product type
            const ptype = (data.product_type || '').toString().toLowerCase();
            const typeSel = document.getElementById('product_type');
            if(typeSel){
                if(ptype === 'digital' || ptype === 'physical'){
                    typeSel.value = ptype; changed++;
                }
                typeSel.dispatchEvent(new Event('change', { bubbles: true }));
            }

            // SEO meta
            const mt = document.getElementById('meta_title');
            if(mt){
                const seoTitle = (data.meta_title || (data.seo && data.seo.title) || data.name || mt.value || '').toString();
                mt.value = seoTitle.substring(0,100); changed++;
            }
            const md = document.getElementById('meta_description');
            if(md){
                let plain = '';
                if(data.meta_description){ plain = data.meta_description; }
                else if(data.seo && data.seo.description){ plain = data.seo.description; }
                else if(data.description){ plain = data.description.replace(/<[^>]+>/g,' ').replace(/\s+/g,' ').trim(); }
                md.value = (plain || md.value || '').toString().substring(0,160); changed++;
            }

            // Pricing & others
            const unitPrice = document.querySelector('input[name="unit_price"]');
            if(unitPrice && data.unit_price !== undefined && data.unit_price !== null){ unitPrice.value = data.unit_price; changed++; }

            const minQty = document.querySelector('input[name="minimum_order_qty"]');
            if(minQty && data.minimum_order_qty){ minQty.value = data.minimum_order_qty; changed++; }

            const stock = document.getElementById('current_stock');
            if(stock && data.current_stock){ stock.value = data.current_stock; changed++; }

            const discType = document.getElementById('product-discount-type');
            if(discType && (data.discount_type === 'flat' || data.discount_type === 'percent')){
                discType.value = data.discount_type; changed++;
                discType.dispatchEvent(new Event('change', { bubbles: true }));
            }
            const disc = document.getElementById('discount');
            if(disc && (data.discount !== undefined && data.discount !== null)){
                disc.value = data.discount; changed++;
            }

            const tax = document.getElementById('tax');
            if(tax && (data.tax !== undefined && data.tax !== null)){
                tax.value = data.tax; changed++;
            }

            const taxModel = document.getElementById('tax_model');
            if(taxModel && (data.tax_model === 'include' || data.tax_model === 'exclude')){
                taxModel.value = data.tax_model; changed++;
            }

            const shipCost = document.querySelector('input[name="shipping_cost"]');
            if(shipCost && (data.shipping_cost !== undefined && data.shipping_cost !== null)){
                shipCost.value = data.shipping_cost; changed++;
            }

            const multiply = document.querySelector('input[name="multiply_qty"]');
            if(multiply && typeof data.multiply_qty === 'boolean'){
                multiply.checked = data.multiply_qty; changed++;
            }
            logLine(`Filled ${changed} fields`, 'success');
        }catch(e){ console.warn('AI fill form error', e); }
    }

    async function attachImages(resp){
        try{
            console.log('attachImages called with:', resp);
            console.log('Thumbnail URL:', resp.thumbnail);
            console.log('Additional images:', resp.additional);
            
            // === THUMBNAIL (Main Product Image) ===
            const thumbInput = document.querySelector('input[name="image"]') || document.querySelector('input.upload-file__input.single_file_input');
            console.log('Thumbnail input found:', !!thumbInput);
            
            if(resp.thumbnail && thumbInput){
                // Fetch and attach thumbnail
                const success = await setFileInputFromUrls(thumbInput, [resp.thumbnail]);
                console.log('Thumbnail setFileInputFromUrls result:', success);
                
                // Update preview card directly
                const card = thumbInput.closest('.upload-file');
                if(card){
                    const img = card.querySelector('.upload-file-img');
                    const textbox = card.querySelector('.upload-file-textbox');
                    const overlay = card.querySelector('.overlay');
                    const removeBtn = card.querySelector('.remove_btn');
                    
                    if(img){
                        img.src = resp.thumbnail;
                        img.style.display = 'block';
                        img.onload = () => {
                            console.log('✓ Thumbnail loaded successfully');
                            logLine('✓ Product thumbnail attached (Main image)', 'success');
                        };
                        img.onerror = () => {
                            console.error('✗ Thumbnail failed to load');
                            logLine('✗ Thumbnail image failed to load', 'error');
                        };
                    }
                    if(textbox) textbox.style.display = 'none';
                    if(overlay) overlay.classList.add('show');
                    if(removeBtn) removeBtn.style.opacity = 1;
                    card.classList.add('input-disabled');
                }
                
                // Update sidebar preview
                $thumb.src = resp.thumbnail; 
                $previewWrap.style.display = '';
            } else {
                console.error('No thumbnail URL or input element found');
                logLine('✗ Thumbnail not found', 'error');
            }
            
            // === ADDITIONAL IMAGES (7 remaining images) ===
            let additionalPicker = document.querySelector('#additional_Image_Section .multi_image_picker') || document.querySelector('.multi_image_picker');
            console.log('Additional picker found:', !!additionalPicker);
            console.log('Additional images count:', resp.additional?.length);
            
            if(resp.additional && resp.additional.length){
                if(!additionalPicker){ 
                    console.log('Waiting for additional images picker...');
                    logLine('Waiting for additional images section...', 'info');
                    additionalPicker = await waitForAdditionalPicker(3000); 
                }
                
                if(additionalPicker){ 
                    console.log(`Attaching ${resp.additional.length} additional images to Spartan picker`);
                    logLine(`Attaching ${resp.additional.length} additional images...`, 'info');
                    
                    // Attach to Spartan Multi Image Picker
                    await attachToSpartan(additionalPicker, resp.additional);
                    
                    console.log('✓ Additional images attached to form');
                    logLine(`✓ ${resp.additional.length} additional images attached to form`, 'success');
                } else {
                    console.error('Additional images picker not found after waiting');
                    logLine('✗ Additional images section not found', 'error');
                }
                
                // Update sidebar preview
                $additional.innerHTML = resp.additional.map(u => `<img src="${u}" class="rounded border" style="width:72px;height:auto;"/>`).join('');
                $previewWrap.style.display = '';
                console.log(`Sidebar preview updated with ${resp.additional.length} images`);
            } else {
                console.warn('No additional images in response');
                logLine('No additional images to attach', 'error');
            }
            
            console.log('=== Image attachment complete ===');
            logLine('Image attachment process completed', 'success');
        }catch(e){ 
            console.error('AI attach images error:', e); 
            logLine('Error attaching images: ' + e.message, 'error');
        }
    }

    $btn.addEventListener('click', async function(){
        $errorsWrap.style.display = 'none';
        $errors.innerText = '';
        $status.innerText = '';
        resetTasks();
        
        // Show progress card
        if ($progressCard) $progressCard.style.display = '';
        
        // Update button state
        const btnContent = $btn.querySelector('.ai-btn-content');
        const btnLoader = $btn.querySelector('.ai-btn-loader');
        if (btnContent) btnContent.style.display = 'none';
        if (btnLoader) btnLoader.style.display = 'flex';
        $btn.disabled = true;

        logLine(t('msg-ai-workflow-started','AI workflow started'));
        setTask('validate','active');
        setProgress(5); setStatusText(t('msg-ai-validating','Validating images'), 5);

        if(!$input.files || !$input.files.length){
            setTask('validate','error');
            const m = t('msg-ai-please-upload', 'Please upload images first');
            logLine(m, 'error');
            toast('warning', m);
            
            // Reset button
            if (btnContent) btnContent.style.display = 'flex';
            if (btnLoader) btnLoader.style.display = 'none';
            $btn.disabled = false;
            return;
        }
        setTask('validate','done');
        logLine('✔ '+t('msg-ai-validating','Validating images'),'success');

        // Analyze
        setTask('analyze','active');
        const fd = new FormData();
        Array.from($input.files).forEach(f => fd.append('images[]', f));

        try{
            const xhr = new XMLHttpRequest();
            xhr.open('POST', analyzeUrl, true);
            xhr.upload.onprogress = function(e){ if(e.lengthComputable){ const p = Math.min(60, Math.round((e.loaded/e.total)*60)); setProgress(p); setStatusText(t('msg-ai-analyzing','Analyzing images'), p); }};
            xhr.onreadystatechange = async function(){
                if(xhr.readyState === 4){
                    if(xhr.status >= 200 && xhr.status < 300){
                        const res = JSON.parse(xhr.responseText);
                        if(res.status === 'success'){
                            setTask('analyze','done'); logLine('✔ '+t('msg-ai-analyzing','Analyzing images'),'success');
                            setTask('map','active'); setProgress(65); setStatusText(t('msg-ai-mapping','Mapping AI output'), 65);
                            // mapping done server-side; proceed
                            setTask('map','done'); logLine('✔ '+t('msg-ai-mapping','Mapping AI output'),'success');

                            setTask('fill','active'); setProgress(70); setStatusText(t('msg-ai-filling','Filling form fields'), 70);
                            await fillForm(res.data);
                            await ensureCategoryChain();
                            await ensureBrandUnit();
                            ensureSkuAndTags();
                            setTask('fill','done'); logLine('✔ '+t('msg-ai-filling','Filling form fields'),'success');
                            toast('success', t('msg-ai-analysis-completed', 'AI analysis completed'));

                            // Generate images
                            setTask('generate','active'); setProgress(75); setStatusText(t('msg-ai-generating','Generating images'), 75);
                            const plain = plainTextFromHtml(res.data?.description || '');
                            const prompt = (`${res.data?.name || ''} product photo, studio, ecommerce, white background. ${plain}`).slice(0, 400);
                            const gi = await fetch(genUrl, { method: 'POST', headers: {'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}, body: JSON.stringify({ prompt: prompt, context: {}, count: 8 })});
                            if(gi.ok){
                                const data = await gi.json();
                                if(data.status === 'success'){
                                    setTask('generate','done'); logLine('✔ '+t('msg-ai-generating','Generating images'),'success');
                                    setTask('attach','active'); setProgress(90); setStatusText(t('msg-ai-attaching','Attaching images'), 90);
                                    await attachImages(data.data);
                                    insertImagesIntoDescription(res.data?.name, data.data);
                                    setTask('attach','done'); logLine('✔ '+t('msg-ai-attaching','Attaching images'),'success');
                                    toast('success', t('msg-ai-images-generated', 'AI images generated'));

                                    setTask('done','done'); setProgress(100); setStatusText(t('msg-ai-completed','All steps completed'), 100);
                                    if($status){ $status.classList.remove('text-muted'); $status.classList.add('text-success'); }
                                    logLine(t('msg-ai-completed','All steps completed'), 'success');
                                    toast('success', t('msg-ai-completed','All steps completed'));
                                    
                                    // Reset button
                                    const btnContent = $btn.querySelector('.ai-btn-content');
                                    const btnLoader = $btn.querySelector('.ai-btn-loader');
                                    if (btnContent) btnContent.style.display = 'flex';
                                    if (btnLoader) btnLoader.style.display = 'none';
                                    $btn.disabled = false;
                                } else {
                                    await fallbackFlow(data.message || 'gen failed');
                                }
                            } else {
                                await fallbackFlow('HTTP '+gi.status);
                            }
                        } else {
                            await fallbackFlow(res.message || 'Analysis failed');
                            return;
                        }
                    } else {
                        await fallbackFlow('HTTP '+xhr.status);
                        return;
                    }
                }
            };
            xhr.setRequestHeader('X-Requested-With','XMLHttpRequest');
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
            xhr.send(fd);
            setStatusText(t('msg-ai-processing', 'Processing...'), 10);
        } catch (err){
            $errorsWrap.style.display = '';
            $errors.innerText = (err && err.message) ? err.message : 'Error';
            logLine($errors.innerText, 'error');
            toast('error', t('msg-ai-something-went-wrong', 'Something went wrong'));
            setProgress(0);
            setTask('done','error');
        }
    });
})();
