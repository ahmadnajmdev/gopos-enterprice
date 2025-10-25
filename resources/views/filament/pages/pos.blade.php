<x-filament-panels::page>
    <div class="w-full mb-4">
        <div class="flex flex-col md:flex-row gap-4 w-full items-center">
            <!-- Hidden Barcode Input (autofocus) -->
            <x-filament::input.wrapper class="w-full md:w-1/4">
                <x-filament::input
                    type="text"
                    wire:model.live="barcodeInput"
                    wire:keydown.enter.prevent="addToCartByBarcode"
                    autofocus
                    aria-label="{{ __('Barcode') }}"
                    placeholder="{{ __('Scan or enter barcode...') }}"
                    class="w-full"
                />
            </x-filament::input.wrapper>
            <!-- Search -->
            <x-filament::input.wrapper class="w-full md:w-1/4">
                <x-filament::input type="text" wire:model.live="search" placeholder="{{ __('Search Products...') }}" class="w-full" />
                <x-slot name="suffix">
                    <x-filament::icon-button icon="heroicon-o-magnifying-glass" color="primary" />
                </x-slot>
            </x-filament::input.wrapper>
            <!-- Category -->
            <x-filament::input.wrapper class="w-full md:w-1/4">
                <x-filament::input.select wire:model.live="selectedCategoryId" class="w-full">
                    <option value="">{{ __('All Categories') }}</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </x-filament::input.select>
            </x-filament::input.wrapper>
            <!-- Order Column -->
            <x-filament::input.wrapper class="w-full md:w-1/4">
                <x-filament::input.select wire:model.live.debounce.300ms="orderColumn" class="w-full">
                    @foreach ($orderColumnOptions as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </x-filament::input.select>
            </x-filament::input.wrapper>
            <!-- Order Direction -->
            <x-filament::input.wrapper class="w-full md:w-1/4">
                <x-filament::input.select wire:model.live="orderDirection" class="w-full">
                    @foreach ($orderDirectionOptions as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </x-filament::input.select>
            </x-filament::input.wrapper>
        </div>
    </div>

    <div class="flex flex-col md:flex-row items-start gap-4 w-full ">
        <!-- Products Grid Section -->
        <div class="flex flex-col items-start w-full md:w-3/5 gap-4">
            <div class="grid grid-cols-2  lg:grid-cols-3 xl:grid-cols-4 gap-4 w-full">
                @forelse ($products as $product)
                    <button wire:click="addToCart({{ $product->id }})" class="w-full">
                        <div
                            class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow flex flex-col h-full min-h-[260px]">
                            <div class="w-full aspect-square mb-2 flex-shrink-0">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                    class="w-full h-full object-cover rounded-lg" style="aspect-ratio: 1 / 1;">
                            </div>
                            <div class="flex flex-col flex-1 justify-between">
                                <h2
                                    class="text-start font-semibold text-gray-900 dark:text-white line-clamp-2 text-ellipsis mt-1">
                                    {{ $product->name }}
                                </h2>
                                <div class="flex flex-col items-start gap-1 mt-2">
                                    @php
                                        // Calculate price in selected currency
                                        $currency = $selectedCurrency ? $currencies->find($selectedCurrency) : $currencies->where('base', true)->first();
                                        $baseCurrency = $currencies->where('base', true)->first();
                                        $displayPrice = $product->price;

                                        if ($currency && $baseCurrency && $currency->id !== $baseCurrency->id) {
                                            $displayPrice = $product->price * $currency->exchange_rate / $baseCurrency->exchange_rate;
                                        }
                                    @endphp
                                    <p class="text-primary-600 font-medium">
                                        {{ number_format($displayPrice, ($currency->decimal_places ?? 0)) }} {{ $currency->symbol ?? 'IQD' }}
                                    </p>
                                    @if ($product->stock == 0)
                                        <p class="text-red-600 font-medium">
                                            {{ __('Out of stock') }}
                                        </p>
                                    @else
                                        <p class="text-gray-500 font-medium">
                                            {{ $product->stock . ' ' . $product->unit?->abbreviation }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </button>
                @empty
                    <div class="col-span-2 lg:col-span-3 xl:col-span-4">
                        <div class="flex flex-col items-center justify-center py-12 px-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                <x-heroicon-o-cube class="w-8 h-8 text-gray-400 dark:text-gray-500" />
                            </div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">
                                {{ __('No products found') }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center">
                                {{ __('Try adjusting your search or filters') }}
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Cart Section -->
        <div class="w-full md:w-2/5">
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 sticky top-4 overflow-hidden">
                <!-- Cart Header -->
                <div class="bg-gradient-to-r from-primary-500 to-primary-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                            <x-heroicon-o-shopping-cart class="w-5 h-5" />
                            {{ __('Shopping Cart') }}
                        </h3>
                        <div class="flex items-center gap-3">
                            <span class="bg-white/20 text-white px-2 py-1 rounded-full text-sm font-medium">
                                {{ count($cart) }} {{ __('items') }}
                            </span>
                            @if($selectedCurrency)
                                @php
                                    $currency = $currencies->find($selectedCurrency);
                                @endphp
                                <span class="bg-white/20 text-white px-2 py-1 rounded-full text-sm font-medium">
                                    {{ $currency->symbol ?? 'IQD' }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                @if (count($cart) > 0)
                    <!-- Cart Items -->
                    <div class="overflow-y-auto max-h-[calc(100vh-28rem)] p-4 space-y-3">
                        @foreach ($cart as $key => $item)
                            @php
                                $subtotal = $item['price'] * $item['stock'];
                                $currency = $selectedCurrency ? $currencies->find($selectedCurrency) : $currencies->where('base', true)->first();
                            @endphp
                            <div
                                class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600 hover:shadow-md transition-all duration-200">
                                <div class="flex items-start gap-3">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}"
                                            class="w-12 h-12 rounded-lg object-cover border border-gray-200 dark:border-gray-600">
                                    </div>

                                    <!-- Product Details -->
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-medium text-gray-900 dark:text-white text-sm line-clamp-2 mb-1">
                                            {{ $item['name'] }}
                                        </h4>
                                        <div
                                            class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mb-2">
                                            <span>{{ number_format($item['price'], ($currency->decimal_places ?? 0)) }} {{ $currency->symbol ?? 'IQD' }}</span>
                                            <span>•</span>
                                            <span>{{ $item['unit'] }}</span>
                                        </div>

                                        <!-- Quantity Controls -->
                                        <div class="flex items-center justify-between">
                                            <div
                                                class="flex items-center bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-600">
                                                <button wire:click="decreaseCartItem({{ $key }})"
                                                    class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-l-lg transition-colors">
                                                    <x-heroicon-o-minus
                                                        class="w-4 h-4 text-gray-600 dark:text-gray-400" />
                                                </button>
                                                <input type="number" wire:model.live="cart.{{ $key }}.stock"
                                                    value="{{ $item['stock'] }}" min="1"
                                                    class="w-12 text-center text-sm font-medium text-gray-900 dark:text-white bg-transparent border-0 focus:ring-0 focus:outline-none">
                                                <button wire:click="increaseCartItem({{ $key }})"
                                                    class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-r-lg transition-colors">
                                                    <x-heroicon-o-plus
                                                        class="w-4 h-4 text-gray-600 dark:text-gray-400" />
                                                </button>
                                            </div>

                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="font-semibold text-primary-600 dark:text-primary-400 text-sm">
                                                    {{ number_format($subtotal, ($currency->decimal_places ?? 0)) }} {{ $currency->symbol ?? 'IQD' }}
                                                </span>
                                                <button wire:click="removeFromCart({{ $key }})"
                                                    class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                                    <x-heroicon-o-trash class="w-4 h-4" />
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="p-4 bg-white dark:bg-gray-800">
                        <form>
                            {{ $this->form }}

                            <div class="border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 p-4 mb-4 mt-4">
                                <div class="space-y-2">
                                    @php
                                        $currency = $selectedCurrency ? $currencies->find($selectedCurrency) : $currencies->where('base', true)->first();
                                        $currencySymbol = $currency->symbol ?? 'IQD';
                                        $decimalPlaces = $currency->decimal_places ?? 0;
                                    @endphp
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600 dark:text-gray-400">{{ __('Subtotal') }}</span>
                                        <span class="font-medium text-gray-900 dark:text-white">
                                            {{ number_format($formData['sub_total'] ?? 0, $decimalPlaces) }} {{ $currencySymbol }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm">
                                        <div class="flex items-center gap-2">
                                            <span class="text-gray-600 dark:text-gray-400">{{ __('Discount') }}</span>
                                            <x-filament::button size="xs" color="gray" wire:click="openDiscountModal"
                                                icon="heroicon-o-tag" class="p-1" title="{{ __('Apply Discount') }}" />
                                        </div>
                                        <span class="font-medium text-red-600 dark:text-red-400">
                                            -{{ number_format((float) ($formData['discount'] ?? 0), $decimalPlaces) }} {{ $currencySymbol }}
                                        </span>
                                    </div>
                                    <div class="border-t border-gray-200 dark:border-gray-600 pt-2">
                                        <div class="flex justify-between text-lg font-bold">
                                            <span class="text-gray-900 dark:text-white">{{ __('Total') }}</span>
                                            <span class="text-primary-600 dark:text-primary-400">
                                                {{ number_format($formData['total_amount'] ?? 0, $decimalPlaces) }} {{ $currencySymbol }}
                                            </span>
                                        </div>
                                    </div>
                                    @if($currency && !$currency->base)
                                        <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
                                            <span>{{ __('Amount in Base Currency') }}</span>
                                            @php
                                                $baseCurrency = $currencies->where('base', true)->first();
                                            @endphp
                                            <span>
                                                {{ number_format($formData['amount_in_base_currency'] ?? 0, ($baseCurrency->decimal_places ?? 0)) }} {{ $baseCurrency->symbol ?? 'IQD' }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col gap-3 mt-4">
                                <!-- Create Invoice Button -->
                                <x-filament::button type="button" wire:click="createInvoice" color="info"
                                    class="w-full">
                                    <div class="flex items-center justify-center gap-2">
                                        <x-heroicon-o-document-text class="w-4 h-4" />
                                        {{ __('Create Invoice') }}
                                    </div>
                                </x-filament::button>

                                <!-- Create Invoice & Payment Button -->
                                <x-filament::button type="button" wire:click="openPaymentModal"
                                    class="w-full bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold py-3 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                                    <div class="flex items-center justify-center gap-2">
                                        <x-heroicon-o-credit-card class="w-5 h-5" />
                                        {{ __('Create Invoice & Payment') }}
                                    </div>
                                </x-filament::button>
                            </div>
                        </form>
                    </div>
                @else
                    <!-- Empty Cart State -->
                    <div class="flex flex-col items-center justify-center py-12 px-6">
                        <div
                            class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                            <x-heroicon-o-shopping-cart class="w-10 h-10 text-gray-400 dark:text-gray-500" />
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                            {{ __('Your cart is empty') }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 text-center">
                            {{ __('Add some products to get started') }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <x-filament::pagination :paginator="$products" class="mt-4" />

    <!-- Discount Modal -->
    <x-filament::modal wire:model="showDiscountModal" id="discount-modal">
        <x-slot name="heading">
            {{ __('Apply Discount') }}
        </x-slot>

        <div class="space-y-4">
            @php
                $currency = $selectedCurrency ? $currencies->find($selectedCurrency) : $currencies->where('base', true)->first();
                $currencySymbol = $currency->symbol ?? 'IQD';
                $decimalPlaces = $currency->decimal_places ?? 0;
            @endphp
            <!-- Current Order Summary -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <h4 class="font-medium text-gray-900 dark:text-white mb-2">{{ __('Order Summary') }}</h4>
                <div class="space-y-1 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">{{ __('Subtotal') }}</span>
                        <span class="font-medium text-gray-900 dark:text-white">
                            {{ number_format($formData['sub_total'] ?? 0, $decimalPlaces) }} {{ $currencySymbol }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">{{ __('Current Discount') }}</span>
                        <span class="font-medium text-red-600 dark:text-red-400">
                            -{{ number_format($formData['discount'] ?? 0, $decimalPlaces) }} {{ $currencySymbol }}
                        </span>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-600 pt-1">
                        <div class="flex justify-between font-semibold">
                            <span class="text-gray-900 dark:text-white">{{ __('Total') }}</span>
                            <span class="text-primary-600 dark:text-primary-400">
                                {{ number_format($formData['total_amount'] ?? 0, $decimalPlaces) }} {{ $currencySymbol }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Discount Type') }}
                </label>
                <x-filament::input.wrapper>
                    <x-filament::input.select wire:model.live="discountType">
                        <option value="fixed">{{ __('Fixed Amount') }}</option>
                        <option value="percentage">{{ __('Percentage') }}</option>
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Discount Amount') }}
                </label>
                <x-filament::input.wrapper>
                    <x-filament::input type="number" wire:model.live="discountAmount" step="0.01" min="0" />
                    <x-slot name="suffix">
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $discountType === 'percentage' ? '%' : $currencySymbol }}
                        </span>
                    </x-slot>
                </x-filament::input.wrapper>
            </div>

            @if ($discountAmount > 0)
                <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg">
                    <p class="text-sm text-blue-700 dark:text-blue-300 font-medium">
                        {{ __('New Discount Amount') }}:
                        {{ $discountType === 'percentage'
                            ? number_format(($formData['sub_total'] ?? 0) * ($discountAmount / 100), $decimalPlaces) .
                                ' ' .
                                $currencySymbol .
                                ' (' .
                                $discountAmount .
                                '%)'
                            : number_format($discountAmount, $decimalPlaces) . ' ' . $currencySymbol }}
                    </p>
                    <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                        {{ __('New Total') }}:
                        {{ number_format(($formData['sub_total'] ?? 0) - ($discountType === 'percentage' ? ($formData['sub_total'] ?? 0) * ($discountAmount / 100) : $discountAmount), $decimalPlaces) }}
                        {{ $currencySymbol }}
                    </p>
                </div>
            @endif
        </div>

        <x-slot name="footerActions">
            <x-filament::button wire:click="closeDiscountModal" color="gray">
                {{ __('Cancel') }}
            </x-filament::button>
            <x-filament::button wire:click="applyDiscount" color="primary">
                {{ __('Apply Discount') }}
            </x-filament::button>
        </x-slot>
    </x-filament::modal>

    <!-- Payment Modal -->
    <x-filament::modal id="payment-modal">
        <x-slot name="heading">
            {{ __('Process Payment') }}
        </x-slot>

        <div class="space-y-4">
            @php
                $currency = $selectedCurrency ? $currencies->find($selectedCurrency) : $currencies->where('base', true)->first();
                $currencySymbol = $currency->symbol ?? 'IQD';
                $decimalPlaces = $currency->decimal_places ?? 0;
            @endphp
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('Total Amount') }}</span>
                    <span class="font-semibold text-gray-900 dark:text-white">
                        {{ number_format($formData['total_amount'] ?? 0, $decimalPlaces) }} {{ $currencySymbol }}
                    </span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('Discount') }}</span>
                    <span class="text-red-600 dark:text-red-400">
                        -{{ number_format($formData['discount'] ?? 0, $decimalPlaces) }} {{ $currencySymbol }}
                    </span>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Payment Amount') }}
                </label>
                <x-filament::input.wrapper>
                    <x-filament::input type="number" wire:model="paymentAmount"
                        step="{{ $decimalPlaces > 0 ? '0.' . str_repeat('0', $decimalPlaces - 1) . '1' : '1' }}"
                        min="0"
                        value="{{ $formData['total_amount'] ?? 0 }}"
                        max="{{ $formData['total_amount'] ?? 0 }}"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
                    <x-slot name="suffix">
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $currencySymbol }}</span>
                    </x-slot>
                </x-filament::input.wrapper>
            </div>

            @if ($paymentAmount > 0 && $paymentAmount < ($formData['total_amount'] ?? 0))
                <div class="bg-yellow-50 dark:bg-yellow-900/20 p-3 rounded-lg">
                    <p class="text-sm text-yellow-700 dark:text-yellow-300">
                        {{ __('Remaining Amount') }}:
                        {{ number_format(($formData['total_amount'] ?? 0) - $paymentAmount, $decimalPlaces) }} {{ $currencySymbol }}
                    </p>
                </div>
            @endif
        </div>

        <x-slot name="footerActions">
            <x-filament::button wire:click="closePaymentModal" color="gray">
                {{ __('Cancel') }}
            </x-filament::button>
            <x-filament::button wire:click="createInvoiceAndPayment" color="primary">
                {{ __('Process Payment') }}
            </x-filament::button>
        </x-slot>
    </x-filament::modal>

    <script>
        function posFilter() {
            return {
                searchQuery: '',
                selectedCategory: '',

                init() {
                    this.searchQuery = '';
                    this.selectedCategory = '';
                },

                filterProduct(productId, productName, categoryId) {
                    const matchesSearch = this.searchQuery === '' ||
                        productName.toLowerCase().includes(this.searchQuery.toLowerCase());
                    const matchesCategory = this.selectedCategory === '' ||
                        categoryId == this.selectedCategory;

                    return matchesSearch && matchesCategory;
                },

                hasVisibleProducts() {
                    const products = document.querySelectorAll('[x-show]');
                    let visibleCount = 0;

                    products.forEach(product => {
                        if (product.style.display !== 'none') {
                            visibleCount++;
                        }
                    });

                    return visibleCount > 0;
                },

                clearFilters() {
                    this.searchQuery = '';
                    this.selectedCategory = '';
                }
            }
        }
    </script>
</x-filament-panels::page>
