@extends('b2b_marketplace::shop.layouts.account')

@section('page_title')
    {{ __('b2b_marketplace::app.shop.supplier.account.messages.title') }}
@endsection

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

@push('css')
    <style>
        #loading {
            display: block;
            display: none;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            text-align: center;
            opacity: 0.7;
            background-color: #fff;
            z-index: 99;
        }
    </style>
@endpush

@section('content-wrapper')
    <div class="account-content">
        @include('shop::customers.account.partials.sidemenu')

        <div class="account-layout">

            <div class="account-head mb-10">
                <span class="account-heading">
                    {{ __('b2b_marketplace::app.shop.supplier.account.messages.supplier-messages') }}
                </span>

                <div class="horizontal-rule"></div>
            </div>

            <div class="main-page-wrapper-msg">
                <div class="mp-main-container" style="padding:0rem;">
                    <chat-component messages='@json($sendedMessages)'></chat-component>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    .mp-customer-message-header {
        width: -webkit-fill-available !important;
    }

    @media only screen and (max-width: 600px) {
        .mp-customer-message-list-container {
            width: 100% !important;
        }
    }

    @media only screen and (min-width: 1300px) {
        .mp-customer-message-header {
            width: 98% !important;
        }
    }

    @media only screen and (min-width: 1460px) {
        .mp-customer-message-header {
            width: 98.5% !important;
        }
    }

    @media only screen and (min-width: 1600) {
        .mp-customer-message-header {
            width: 99% !important;
        }
    }

    @media only screen and (min-width: 1800) {
        .mp-customer-message-header {
            width: 100% !important;
        }
    }
</style>

@push('scripts')
    <script type="text/x-template" id="chat-template">
        <div class="mp-customer-message-main-container">

            <div class="mp-customer-message-list-container" id="msg-thread">
                <div class="customer-message-search">
                     <input type="text" class="customer-supplier-search-box" placeholder="Search" autocomplete="off" v-model.lazy="term" v-debounce="500" id="searchMsg.name" name="searchMsg.name" v-model="messageName" value="{{ old('product_name') }}">
                </div>

                <div class="customer-supplier-message-list">

                    <div class="customer-supplier-message"
                        v-for="messageData in Messages"
                        v-on:click="getMessages(messageData.id)"
                        v-if="messageName == null"
                        :id="messageData.id"
                        >

                        {{-- <div class="customer-supplier-message-list-image">
                            <img src="" class="icon customer-icon active">
                        </div> --}}

                        <a class="message-logo">
                            <i class="icon customer-icon active"></i>
                        </a>

                        <div class="customer-supplier-message-list-information">
                            <div class="customer-supplier-message-name">
                                @{{messageData.customerName}}

                                <span class="message-unseen-count" v-if="messageData.msgCount > 0">
                                    @{{messageData.msgCount}}
                                </span>
                            </div>

                            <div class="customer-supplier-message-message" v-if="messageData.role == 'customer' && messageData.message.length<=4">
                                Customer: @{{messageData.message}}
                                <div class="customer-supplier-message-date">
                                @{{messageData.createdAt}}
                            </div>
                            </div>

                            <div class="customer-supplier-message-message" v-if="messageData.role == 'supplier' && messageData.message.length<=8">
                                You: @{{messageData.message}}
                                <div class="customer-supplier-message-date">
                                @{{messageData.createdAt}}
                            </div>
                            </div>

                            <div class="customer-supplier-message-message" v-if="messageData.role == 'customer' && messageData.message.length>4">
                                Customer: @{{messageData.message.substring(0,4)+".."}}
                                <div class="customer-supplier-message-date">
                                @{{messageData.createdAt}}
                            </div>
                            </div>

                            <div class="customer-supplier-message-message" v-if="messageData.role == 'supplier' && messageData.message.length>8">
                                You: @{{messageData.message.substring(0,8)+".."}}
                                <div class="customer-supplier-message-date">
                                @{{messageData.createdAt}}
                            </div>
                            </div>


                        </div>

                    </div>

                    <div class="customer-supplier-message"
                        v-for="messageData in searchMessageData"
                        v-on:click="getMessages(messageData.id)"
                        v-if="messageName != null"
                        :id="messageData.id"
                        >

                        <a class="message-logo">
                            <i class="icon customer-icon active"></i>
                        </a>

                        <div class="customer-supplier-message-list-information">
                            <div class="customer-supplier-message-name">
                                @{{messageData.customerName}}

                                <span class="message-unseen-count" v-if="messageData.msgCount > 0">
                                    @{{messageData.msgCount}}
                                </span>
                            </div>

                            <div class="customer-supplier-message-date">
                                @{{messageData.createdAt}}
                            </div>

                            <div class="customer-supplier-message-message">
                                @{{messageData.message}}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="mp-customer-message-container" v-show="showChatBox" id="msg-data">
                <div class="mp-customer-message-header">
                    <div class="mp-customer-message-header-info">

                        <span class="menu-back" v-on:click="backToMsgList()" style="margin-top: 10px;
                        margin-right: 10px;" v-if="isMobileDevice">
                            <i class="fa fa-angle-double-left" style="font-size:24px"></i>
                        </span>

                        <div class="mp-customer-supplier-img">
                            <a class="message-logo">
                                <i class="icon customer-icon active"></i>
                            </a>
                        </div>

                        <div class="mp-customer-supplier-info">
                            <div class="mp-customer-supplier-name">@{{supplierName}}</div>
                            <div class="mp-customer-supplier-type">
                                {{ __('b2b_marketplace::app.shop.supplier.account.messages.supplier') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mp-customer-msg-body" id="chat">
                    <div v-for="chatDetail,index in chatDetails">

                        <div class="mp-customer-sender-block" v-if="chatDetail.role == 'customer'" style="">

                            <div class="mp-customer-supplier-msg" >
                                <div>
                                    @{{chatDetail.message}}
                                </div>

                                <div id="mp-msg-attachments-container2"></div>
                            </div>

                            <div class="mp-customer-supplier-msg-time">@{{chatDetail.created_at}}</div>
                        </div>

                        <div class="mp-customer-receiver-block" v-if="chatDetail.role == 'supplier'">

                            <div class="mp-customer-message-name">
                                @{{supplierName}}
                            </div>

                            <div class="mp-customer-supplier-msg">
                                <div>@{{chatDetail.message}}</div>
                                <div id="mp-msg-attachments-container3"></div>
                            </div>

                            <div class="mp-customer-supplier-msg-time">@{{chatDetail.created_at}}</div>
                        </div>

                    </div>
                </div>

                <section enctype="multipart/form-data" data-vv-scope="msg-form">
                    @csrf()
                    <div class="mp-customer-supplier-msg-box">
                        <div class="mp-customer-msg-box-textarea">

                            <div :class="[errors.has('msg-form.message.newMessage') ? 'has-error' : '']">
                                <textarea type="text" class="control" id="message.newMessage" name="message.newMessage" v-validate="'required'" v-model="message.newMessage" data-vv-as="&quot;{{ __('Message') }}&quot;" placeholder="Type your message here." data-vv-scope="msg-form"></textarea>

                                <span class="control-error" v-if="errors.has('msg-form.message.newMessage')" style="color:#fc6868; float:left;">@{{ errors.first('msg-form.message.newMessage') }}</span>
                            </div>
                        </div>



                        <div class="mp-customer-msg-box-action">
                            <button class="theme-btn" type="button" @click="saveMessage('msg-form')" style="margin-top: 13px;
                            margin-left: 5px;" :disabled="disable_button">
                                <span>
                                    {{ __('b2b_marketplace::app.supplier.account.message.send') }}
                                </span>
                            </button>
                        </div>

                    </div>

                    <div id="loading">
                        <div class="cp-spinner cp-round spinner" id="loader"> </div>
                    </div>
                </section>

            </div>
        </div>
    </script>

    <script type="text/javascript">
        Vue.component('chat-component', {

            template: '#chat-template',

            props: {
                messages: {
                    type: [Array, String, Object],
                    required: false,
                    default: (function() {
                        return [];
                    })
                },

                url: String
            },

            data: function() {
                return {
                    chatDetails: [],
                    newMessage: {},
                    messageId: '',
                    supplierName: '',
                    showChatBox: false,
                    message: [],

                    term: "",
                    messageName: null,
                    searchMessageData: null,
                    isMobileDevice: this.isMobile(),
                    disable_button: false
                }
            },

            watch: {
                'term': function(newVal, oldVal) {

                    if (newVal == '')
                        this.messageName = null;

                    this.search()
                }
            },

            computed: {
                Messages: function() {
                    return JSON.parse(this.messages)
                }
            },

            methods: {

                'isMobile': function() {
                    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator
                            .userAgent)) {
                        return true;
                    } else {
                        return false;
                    }
                },

                scrollToBottom: function(id) {

                    var container = document.getElementById(id);

                    $('#' + id).animate({
                        scrollTop: container.scrollHeight + 900
                    }, 500);
                },

                search() {
                    if (this.term.length > 0) {

                        this_this = this;
                        this.is_searching = true;

                        this.$http.post("{{ route('b2b_marketplace.shop.customer.messages.search') }}", {
                                query: this.term
                            })
                            .then(function(response) {

                                this_this.searchMessageData = response.data;
                                this_this.is_searching = false;
                            })

                            .catch(function(error) {
                                this_this.is_searching = false;
                            })
                    }
                },

                backToMsgList: function() {
                    console.log('run the back');
                    $('.mp-customer-message-container').css("display", "none");
                    $('.mp-customer-message-list-container').css("display", "block");
                    $('.customer-supplier-message').css("background", "none");
                    $('.customer-supplier-message').css("color", "#000000D4");

                },

                getMessages: function(msgId) {

                    this.messages;

                    var this_this = this;
                    this.showChatBox = true;

                    //Getting the screensize
                    var width = $(window).width();

                    //Execute it if mobile size
                    if (width < 720) {
                        $('.mp-customer-message-list-container').css("display", "none");
                        $('.mp-customer-message-container').css("width", "100%");
                        $('.mp-customer-message-container').css("display", "block");
                    }
                    if (width > 720) {
                        $('.mp-customer-message-list-container').css("display", "block");
                        $('.mp-customer-message-container').css("display", "block");
                    }

                    var myResize = function() {
                        var width = $(window).width();
                        if (width < 720) {
                            $('.mp-customer-message-list-container').css("display", "none");
                            $('.mp-customer-message-container').css("width", "100%");
                            $('.mp-customer-message-container').css("display", "block");
                        }
                        if (width > 720) {
                            $('.mp-customer-message-list-container').css("display", "block");
                            $('.mp-customer-message-container').css("display", "block");
                        }
                    };

                    // Execute it every resize
                    $(window).resize(myResize);


                    this_this.$http.post(
                            "{{ route('b2b_marketplace.customers.account.supplier.messages.show') }}", {
                                messageId: msgId
                            })
                        .then(function(response) {

                            this_this.messageId = msgId;

                            for (var prop in this_this.Messages) {

                                if (this_this.Messages[prop].id == msgId) {

                                    document.getElementById(msgId).style =
                                        'background:#98999e; color: whitesmoke;';
                                } else {
                                    if (document.getElementById(this_this.Messages[prop].id) != null) {

                                        document.getElementById(this_this.Messages[prop].id).style =
                                            'background: none';
                                    }
                                }
                            }

                            for (var index in response.data.messages) {

                                var mydate = new Date(response.data.messages[index].created_at);
                                var month = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                                    "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                                ][mydate.getMonth()];

                                response.data.messages[index].created_at = month + '-' + mydate.getDate() +
                                    '-' + mydate.getFullYear() + ' ' + mydate.getHours() + ' : ' + mydate
                                    .getMinutes();
                            }

                            this_this.chatDetails = response.data.messages;
                            this_this.supplierName = response.data.supplierName;

                            this_this.scrollToBottom('chat');
                        })
                },

                saveMessage: function(scope) {

                    this.$validator.validateAll(scope).then(result => {
                        if (result) {
                            this.disable_button = true;

                            $('#loading').show();

                            this.$http.post(
                                "{{ route('b2b_marketplace.customers.account.supplier.messages.store') }}", {
                                    newMessage: this.message.newMessage,
                                    messageId: this.messageId,
                                }).then(response => {

                                if (response.data == 'error') {

                                    window.showAlert(`alert-danger`, this.__(
                                        'shop.general.alert.danger'), 'Empty Message');

                                    this.getMessages(this.messageId);
                                } else {
                                    window.showAlert(`alert-success`, this.__(
                                            'shop.general.alert.success'),
                                        'Message Sent To Supplier Successfully.');
                                }

                                this.message = [];
                                this.getMessages(this.messageId);
                                this.disable_button = false;

                                $('#loading').hide();
                                this_this.scrollToBottom('chat');
                            })
                        }
                    });

                },
            },
        });
    </script>
@endpush
