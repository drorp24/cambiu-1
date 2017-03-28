@extends('admin.layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox-content forum-container">

                <div class="search-form">
                    <form action="" method="get">
                        <div class="input-group">
                            <input type="text" placeholder="Search ..." name="search" class="form-control input-lg" value="{{\Illuminate\Support\Facades\Input::get('search')}}">
                            <div class="input-group-btn">
                                <button class="btn btn-lg btn-primary" type="submit">
                                    Search
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="hr-line-dashed"></div>
                <button id="update" class="btn-green btn btn-md" style="margin: 5px;background-color: #2C8F7B;border-color: #2C8F7B;">Update</button>
                <div class="table-responsive" data-first-pos="{{ $aExchangeRates->first()->pos }}">
                    <table class="footable table table-stripped toggle-arrow-tiny default breakpoint footable-loaded">
                        <tr>
                            <th class="footable-first-column">
                                <input type="checkbox" name="check-all" class="i-checks">
                            </th>
                            <th>Symbol</th>
                            <th>Title</th>
                            <th>Exchange Rate</th>
                            <th>Buy</th>
                            <th>Buy Rate</th>
                            <th>Sell</th>
                            <th>Sell Rate</th>
                            <th>Visible</th>
                            <th class="text-right footable-last-column">Action</th>
                        </tr>
                        @foreach($aExchangeRates->all() as $iIdx => $oExchangeRate)
                        <tr class="footable-{{$iIdx % 2 == 0 ? 'odd' : 'even'}}" style="display: table-row;" id="rate_{{ $oExchangeRate->id }}">
                            <td class="check-mail footable-first-column">
                                <input type="checkbox" name="id[]" data-name="id" value="{{ $oExchangeRate->id }}" class="i-checks">
                            </td>
                            <td>
                                {{ $oExchangeRate->symbol }}
                            </td>
                            <td>
                                {{ $oExchangeRate->title }}
                            </td>
                            <td class="rate">
                                {{ $oExchangeRate->exchangeRate }}
                            </td>
                            <td>
                                <input type="text"
                                       pattern="[0-9]"
                                       title="Numbers only"
                                       value="{{ sprintf('%01.6f', $oExchangeRate->buy) }}"
                                       class="form-control buy col-md-4 rate-value-input"
                                       name="buy[]" data-name="buy"
                                       style="width:50%;"
                                        {{ ($oExchangeRate->type_buy === 'disabled') ? 'disabled' : ''}}>
                                <select name="type_buy[]" data-name="type_buy" data-placeholder="Choose a trade type..." class="chosen-select col-md-6" style="width:40%;" tabindex="4">
                                    <option {{ $oExchangeRate->type_buy === 'disabled' ? 'selected' : '' }} value="disabled">Disabled</option>
                                    <option {{ $oExchangeRate->type_buy === 'percent' ? 'selected' : '' }} value="percent">Margin(%)</option>
                                    <option {{ $oExchangeRate->type_buy === 'fixed' ? 'selected' : '' }} value="fixed">Flat Rate</option>
                                </select>
                            </td>
                            <td class="buy_rate">
                                {{ $oExchangeRate->getBuyRateAttribute() }}
                            </td>
                            <td>

                                <input type="text"
                                       pattern="[0-9]"
                                       title="Numbers only"
                                       value="{{ sprintf('%01.6f', $oExchangeRate->sell) }}"
                                       class="form-control margin-rate-input col-md-4 rate-value-input"
                                       name="sell[]" data-name="sell"
                                       style="width:50%;"
                                        {{ ($oExchangeRate->type_sell === 'disabled') ? 'disabled' : ''}}>
                                <select name="type_sell[]" data-name="type_sell" data-placeholder="Choose a trade type..." class="chosen-select col-md-6" style="width:40%;" tabindex="4">
                                    <option {{ ($oExchangeRate->type_sell === 'disabled') ? 'selected' : '' }} value="disabled">Disabled</option>
                                    <option {{ ($oExchangeRate->type_sell === 'percent') ? 'selected' : '' }} value="percent">Margin(%)</option>
                                    <option {{ ($oExchangeRate->type_sell === 'fixed') ? 'selected' : '' }} value="fixed">Flat Rate</option>
                                </select>
                            </td>
                            <td class="sell_rate">
                                {{ $oExchangeRate->getSellRateAttribute() }}
                            </td>
                            <td>
                                <div class="flag-toggle-2">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" class="visible-switch"
                                               {{ ($oExchangeRate->visible == 1) ? 'checked' : '' }}
                                               data-id="{{ $oExchangeRate->id }}"
                                               data-toggle="toggle"
                                               data-size="mini"
                                               data-on-text="Visible"
                                               data-off-text="Hidden"
                                        >
                                        <input type="hidden" data-name="visible" value="0" name="visible" id="vs_{{ $oExchangeRate->id }}">
                                    </label>
                                </div>
                            </td>
                            <td class="text-right footable-last-column">
                                <div class="btn-group">
                                    <button class="btn-warning btn btn-sm single-row-update">
                                        Update
                                    </button>

                                </div>
                            </td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="7" class="footable-visible">
                                @if($aExchangeRates->render() != '')
                                    {{ $aExchangeRates->appends(\Illuminate\Support\Facades\Input::except(array('page')))->render() }}
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                @if(empty($aExchangeRates->all()))
                    <div class="alert alert-info alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        You don't have any exchangeRates!
                    </div>
                @endif
            </div>
        </div>

    </div>
@stop

@section('footer')
    <script type="text/javascript">
        $(document).ready(function () {

            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            $('.visible-switch')
                .bootstrapSwitch()
                .on('switchChange.bootstrapSwitch', function(event, state) {
                   var elem = $('#vs_' + $(this).data('id')).val(state ? 1 : 0);
                    somethingIsGoingOn(elem.parents('tr').first());
                });

            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green'
            });

            $('.i-checks:first')
                .on('ifChecked', function(event) {
                    $('.i-checks:not(:first)').iCheck('check');
                })
                .on('ifUnchecked', function(event) {
                    $('.i-checks:not(:first)').iCheck('uncheck');
                });

            $('.chosen-select')
                .chosen({ width: '40%' })
                .on('change',function () {

                    somethingIsGoingOn($(this).parents('tr').first());

                    var selected = $(this).val();
                    var input = $(this).prev(".rate-value-input");
                    if(selected == 'disabled') {
                        input.prop('disabled', true);
                        if (input.val() == '') {
                            input.val('0.000000');
                        }
                    } else {
                        input.prop('disabled', false);
                        setTimeout(function () {
                            input.focus();
                            if (input.val() == 0) {
                                input.val('');
                            }
                        }, 1);

                    }
                    input.trigger('change');
                });

            $('.rate-value-input')
                .focusout(function(){
                    if ($(this).val() == '') {
                        $(this).val('0.000000');
                    }
                })
                .on('change keyup',function (event) {

                    var value = parseFloat($(this).val());
                    if(isNaN(value)) {
                        value = 0;
                    }
                    var row = $(this).parents('tr').first();

                    if(event.type == 'keyup') {
                        somethingIsGoingOn(row);
                    }

                    var exchange_rate = parseFloat(row.find('.rate').text());
                    var trade = $(this).data('name');
                    var select = '';

                    if(trade == 'buy') {
                        select = row.find('select[name="type_buy[]"]').val();
                        if(select == 'percent') {
                            calculateBuyRate(row, value, exchange_rate, false);
                        } else if (select == 'fixed') {
                            calculateBuyRate(row , value , exchange_rate , true);
                        }
                    } else if (trade == 'sell') {
                        select = row.find('select[name="type_sell[]"]').val();
                        if(select == 'percent') {
                            calculateSellRate(row , value , exchange_rate ,false);
                        } else if (select == 'fixed') {
                            calculateSellRate(row , value , exchange_rate, true);
                        }
                    }
                });

            function calculateBuyRate(row, value, exchange_rate, flatRate) {
                var field = row.find('.buy_rate');
                if(flatRate) {
                    field.text((exchange_rate + value).toFixed(6));
                } else {
                    field.text( (exchange_rate * ((value + 100) / 100)).toFixed(6) );
                }
            }

            function calculateSellRate(row, value, exchange_rate, flatRate) {

                var field = row.find('.sell_rate');
                if(flatRate) {
                    field.text((exchange_rate - value).toFixed(6));
                } else {
                    field.text( (exchange_rate * ((100 - value) / 100)).toFixed(6) );
                }
            }

            function somethingIsGoingOn(row) {
                row.css('background-color','lightyellow').addClass('triggered');
            }

            $('#update').click(function () {

                var data = {};
                var that = $(this);

                $('.i-checks').each(function () {

                    var row_data = {};
                    var row = $(this).parents('tr').first();
                    if ($(this).prop("checked")) {
                        row.find('[data-name]').each(function () {
                            row_data[$(this).data('name')] =  $(this).val();
                        });
                        data[row.attr('id').replace("rate_", "")] = row_data;
                    }
                });

                if(Object.keys(data).length) {
                    call_ajax(data);
                }
                function call_ajax(data) {
                    $.ajax({
                        method: "POST",
                        url: "trade/collection",
                        data: data,
                        success: function (result) {
                            $('.triggered').css('background-color','white');
                            that.after('<div id="msg"  class="alert alert-success"> <button type="button" class="close" data-dismiss="alert">x</button> <strong>Done! </strong>The fields have been updated successfully.</div>');
                            setTimeout(function () {
                                $('#msg').remove();
                            }, 5000);

                        }, error: function (result) {
                            that.after('<div id="msg" class="alert alert-danger"> <button type="button" class="close" data-dismiss="alert">x</button> <strong>Error! </strong>All fields must be numeric. </div>');
                            setTimeout(function () {
                                $('#msg').remove();
                            }, 5000);
                        }
                    });
                }
            });

            $(".single-row-update").on('click', function(){
                var that = $(this);
                var row = that.parents('tr').first();
                var data = {};
                row.find('[data-name]').each(function () {
                    data[$(this).data('name')] =   $(this).val();
                });

                $.ajax({
                    method: "POST",
                    url: "trade/update",
                    data: data,
                    success: function(result) {
                        if(result.success === true) {
                            row.css('background-color','white');
                            row.after('<div id="msg" style="position: absolute;left:30%;z-index: 1000;" class="alert alert-success"><button type="button" class="close" data-dismiss="alert">x</button> <strong>Done! </strong>The field has been updated successfully.</div>');
                            setTimeout(function(){
                                $('#msg').remove();
                            }, 2000);
                        }


                    }, error: function (xhr, status, error) {
                        var data = JSON.parse(xhr.responseText);
                        for(var i in data){
                            for(var ii in data[i]){
                                row.after('<div id="msg" style="position: absolute;left:30%;z-index: 1000;" class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">x</button> <strong>Error! </strong>' + data[i][ii] + ' </div>');
                            }
                        }
                        setTimeout(function(){
                            $('#msg').remove();
                        }, 3000);
                    }
                })
            });
        });
    </script>
@endsection