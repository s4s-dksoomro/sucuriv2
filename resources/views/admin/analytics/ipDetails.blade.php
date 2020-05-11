<table class="table table-striped table-condensed IPdatatable">

                <thead>
                    <tr>
                        <th>RayID</th>

                        <th>Date</th>
                        
                        <th>Method</th>
                        <th>Protocol</th>
                        <th>Host</th>
                        <th style="max-width: 200px;">URI</th>
                        <th>Device Type</th>

                    </tr>
                </thead>

                <tbody>
                    @if (count($deviceType) > 0)
                        @foreach ($deviceType as $event)
                            <tr>
                                <td>{{ $event['RayID'] }}</td>

                                <td>
                                  {{ $event['EdgeStartTimestamp'] }}
                                </td>

                               
                                <td>
                                  {{ $event['ClientRequestMethod'] }}
                                </td>
                                <td>
                                  {{ $event['ClientRequestProtocol'] }}
                                </td>
                                <td>
                                  {{ $event['ClientRequestHost'] }}
                                </td>
                                <td>
                                  {{ $event['ClientRequestURI'] }}
                                </td>
                                
                                <td>
                                  {{ $event['ClientDeviceType'] }}
                                </td>
                                

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9">@lang('global.app_no_entries_in_table')</td>
                        </tr>
                    @endif
                </tbody>
            </table>