<section enctype="multipart/form-data" data-vv-scope="product-form">

    <div class="rfq-product-section">

        <div class="product-head">
            <div class="add-btn" style="float: right;
            margin-bottom: 10px;">

                <button class="btn btn-lg btn-primary" type="button" style="background: black !important;" @click="nevigate()">
                    {{ __('Back') }}
                </button>

                <button class="btn btn-lg btn-primary" type="submit" :disabled="disable_button">
                    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.rfq-title') }}
                </button>
            </div>

            <div class="product-info">
                <span>{{ __('b2b_marketplace::app.shop.supplier.account.rfq.product-information') }}</span>
            </div>
        </div>

        <div class="control-group">
            <table class="rfq-product-table">
                <thead>
                    <tr>
                        <th>{{ __('b2b_marketplace::app.shop.supplier.account.rfq.product-name') }}</th>
                        <th>{{ __('b2b_marketplace::app.shop.supplier.account.rfq.quote-quantity') }}</th>
                        <th>{{ __('b2b_marketplace::app.shop.supplier.account.rfq.price') }}</th>
                        <th>{{ __('b2b_marketplace::app.shop.supplier.account.rfq.samples') }}</th>
                        <th>{{ __('b2b_marketplace::app.shop.supplier.account.rfq.action') }}</th>
                    </tr>
                </thead>

                <tbody class="rfq-product-table-tbody">
                    <tr id="table-row-id" v-if="selectedProduct != ''" v-for = "product in selectedProduct" v-if='product.product_id'>
                        <td>@{{product.product_name}}</td>
                        <td>@{{product.quantity}}</td>
                        <td>@{{product.priceperqty}}</td>
                        <td v-if='product.is_sample == 1'>Yes</td>
                        <td v-if='product.is_sample == 0'>No</td>
                        <td>
                            <span title="Remove" class="icon trash-icon" v-on:click= "deleteProduct(product.product_id)"></span>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="supplier-products-row-container" style="padding: 15px;
                background: #f5f5f5;
                border-radius: 3px;">
                <div class="supplier-header-txt fieldset">
                    <div class="field-required">
                        <label class="label-text required">{{ __('b2b_marketplace::app.shop.supplier.account.rfq.action') }}</label>

                        <div class="b2b-rfq-item-category-panel">
                                <div class="container">

                                    <ul class="ks-cboxtags">
                                        <li v-for="category in Categories"
                                            v-on:click ="checkedBox(category.category_id)">

                                        <input type="checkbox" :checkId = "category.category_id" data-id="category.category_id" value= "category.category_id" name="category[]" v-model="checkedCategoryId">
                                            <label for="checkboxOne" class="checked" :id="category.category_id">@{{category.name}}</label>
                                        </li>
                                    </ul>

                                </div>
                        </div>
                    </div>

                    <div class="control-group" :class="[errors.has('product_data.name') ? 'has-error' : '']">
                        <label class="label-text required">
                            {{ __('b2b_marketplace::app.shop.supplier.account.rfq.product-name') }}
                        </label>

                        <input type="text" style="width:100%;" class="control dropdown-toggle"
                        placeholder="enter key word" autocomplete="off" v-model.lazy="term" v-debounce="500"id="product_data.name" name="product_data.name" v-model="product_name" v-validate="'required'" value="{{ old('product_name') }}" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.rfq.product-name') }}&quot;"/>
                        <span class="control-error" v-if="errors.has('product_data.name')">@{{ errors.first('product_data.name') }}</span>

                        <label class="label-text" style="color:#0041ff" v-show="product_data.product_id && product_data.product_id != null">
                            Product Price: @{{formatedPrice}}
                        </label>

                        <div class="dropdown-list bottom-left product-search-list">
                            <div class="dropdown-container">
                                <ul>
                                    <li v-if="products.length" class="table dropdown-toggle">
                                        <table id="productTable" class="productTable">
                                            <tbody>
                                                <tr v-for='(product, index) in products' v-on:click = "getProductId()" :id="product.id" :name="product.name" :price="product.formated_price">
                                                    <td>
                                                        <img v-if="!product.base_image" src="{{ bagisto_asset('themes/b2b/assets/images/Default-Product-Image.png') }}" style="height: 25px; border: 1px solid black;"/>
                                                        <img v-if="product.base_image" :src="product.base_image" style="box-shadow: 4px 3px 4px 1px #c7c7c7;"/>
                                                    </td>
                                                    <td>
                                                        @{{ product.name }}
                                                    </td>
                                                    <td v-if='product.is_config'>
                                                        As Low As @{{ product.formated_price }}
                                                    </td>
                                                    <td v-else='product.is_config'>
                                                        @{{ product.formated_price }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </li>

                                    <li v-if="!products.length && term.length > 2 && !is_searching">
                                        {{ __('b2b_marketplace::app.shop.supplier.account.rfq.no-result-found') }}
                                    </li>

                                    <li v-if="term.length < 3 && !is_searching">
                                        {{ __('b2b_marketplace::app.shop.supplier.account.rfq.press-any-key') }}
                                    </li>

                                    <li v-if="is_searching">
                                        {{ __('b2b_marketplace::app.shop.supplier.account.rfq.searching') }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="control-group" :class="[errors.has('product_data.description') ? 'has-error' : '']">
                        <label for="label-text required" class="required">
                            {{ __('b2b_marketplace::app.shop.supplier.account.rfq.product-description') }}
                        </label>
                        <textarea type="text" style="width:100%;" class="control" id="product_data.description" name="product_data.description" v-model="product_data.description" v-validate="'required'" value="{{ old('description') }}" data-vv-as="&quot;Description&quot;"></textarea>
                        <span class="control-error" v-if="errors.has('product_data.description')">@{{ errors.first('product_data.description') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('product_data.quantity') ? 'has-error' : '']">
                        <label class="label-text required">
                            {{ __('b2b_marketplace::app.shop.supplier.account.rfq.product-qty') }}
                        </label>

                        <input type="text" style="width:100%;" class="control product-search-box" id="product_data.quantity" name="product_data.quantity" v-model="product_data.quantity" v-validate="'required|numeric'" value="{{ old('quantity') }}" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.rfq.product-qty') }}&quot;"  @keypress="isNumber($event)"/>
                        <span class="control-error" v-if="errors.has('product_data.quantity')">@{{ errors.first('product_form.quantity') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('product_data.priceperqty') ? 'has-error' : '']">
                        <label class="label-text required">
                            {{ __('b2b_marketplace::app.shop.supplier.account.rfq.price-per-qty') }}
                        </label>

                        <input type="text" style="width:100%;" class="control product-search-box" id="product_data.priceperqty" name="product_data.priceperqty" v-model="product_data.priceperqty" v-validate="'required|numeric'" value="{{ old('priceperqty') }}" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.rfq.price-per-qty') }}&quot;"  @keypress="isDecimal($event)">

                        <span class="control-error" v-if="errors.has('product_data.priceperqty')">@{{ errors.first('product_data.priceperqty') }}</span>
                    </div>

                </div>

                <div class="supplier-header-txt fieldset">
                    <div class="control-group" :class="[errors.has('product_data.is_sample') ? 'has-error' : '']">
                        <label for="is_sample" class="required">
                            {{ __('b2b_marketplace::app.shop.supplier.account.rfq.is-samples') }}
                        </label>
                        <select style="width:100%;" class="control" v-validate="'required'" id="is_sample" name="product_data.is_sample" v-model="product_data.is_sample" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.account.rfq.is-samples') }}&quot;">
                            <option value="1">{{ __('b2b_marketplace::app.shop.supplier.account.rfq.sample-require-yes') }}</option>
                            <option value="0">{{ __('b2b_marketplace::app.shop.supplier.account.rfq.sample-require-no') }}</option>
                        </select>


                        <span class="control-error" v-if="errors.has('product_data.is_sample')">@{{ errors.first('product_data.is_sample') }}</span>
                    </div>
                </div>

                <div class="hr-line" style="border-bottom: 1px solid #aea1a1;
                margin-bottom: 15px;"></div>
            </div>

            <input type ="hidden" name= "rfqInfo" :value="JSON.stringify(rfq_info)"/>

            <input type ="hidden" v-for="(product, index) in selectedProduct" name= "products[]" :value="JSON.stringify(selectedProduct[index])"/>
        </div>

    </div>
</section>