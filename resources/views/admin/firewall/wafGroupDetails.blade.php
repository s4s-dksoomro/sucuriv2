<table class="table table-striped table-condensed RulesDatatable">

                <thead>
                    <tr>
                        <th>RuleID</th>

                        <th>Name/Description</th>
                        
                        <th>Mode</th>
                        

                    </tr>
                </thead>

                <tbody>
                    @if (count($wafRules) > 0)
                        @foreach ($wafRules as $wafRule)

                            <tr>
                                <td>{{ $wafRule['record_id'] }}</td>

                                <td>
                                  {{ $wafRule['description'] }}
                                </td>

                               
                                <td>

                                    
                                    <select  rule-id="{{ $wafRule->id }}" class="wafRuleChange select2">
                                    @foreach(unserialize($wafRule->allowed_modes) as $mode)
                                    <option value="{{ $mode }}" @if($mode==$wafRule->mode) selected="selected" @endif>{{ $mode }}</option>
                                        
                                    @endforeach
                                    </select>
                                    
                

                                </td>
                                
                                

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3">@lang('global.app_no_entries_in_table')</td>
                        </tr>
                    @endif
                </tbody>
            </table>