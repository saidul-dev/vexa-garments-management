@foreach($samples as $sample)
    <tr>
        <td>{{ $loop->index+1 }} <i class="{{ request('id') == $sample->id ? 'fas fa-bell text-red' : '' }}"></i></td>
        <td><a href="{{ route('sample.print',$sample->id) }}" target="_blank" class="text-primary">{{ $sample->order->order_no ?? '' }}</a></td>

        <td>{{ $sample->consignee }}</td>
        <td>
            @foreach($sample->styles ?? [] as $style)
                {{ $style }}@if(!$loop->last),@endif
            @endforeach
        </td>
        <td>
            @foreach($sample->items ?? [] as $item)
                {{ $item }}@if(!$loop->last),@endif
            @endforeach
        </td>
        <td>
            @foreach($sample->types ?? [] as $type)
                {{ $type }}@if(!$loop->last),@endif
            @endforeach
        </td>
        <td>
            @foreach($sample->quantities ?? [] as $quantity)
                {{ $quantity }}@if(!$loop->last),@endif
            @endforeach
        </td>
        <td>
            <div class="{{ $sample->status == 1 ? 'badge bg-warning' : ($sample->status == 2 ? 'badge bg-primary' : 'badge bg-danger') }}" title="{{ $sample->reason }}">
                {{ $sample->status == 1 ? 'Pending' : ($sample->status == 2 ? 'Approved' : 'Reject') }}
            </div>
        </td>
        <td class="print-d-none">
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a target="_blank" href="{{ route('sample.print',$sample->id) }}"><i class="fas fa-print"></i>{{('Print')}}</a></li>
                    @can('samples-status')
                        @if($sample->status == 1)
                            <li>
                                <a href="javascript:void(0)" action="{{ route('sample.status', ['id' => $sample->id, 'status' => 2]) }}" class="change-status">
                                    <i class="far fa-check-circle"></i>
                                    {{__('Approved') }}
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" action="{{ route('sample.status', ['id' => $sample->id, 'status' => 0]) }}" class="change-status">
                                    <i class="far fa-times-circle"></i>
                                    {{__('Reject') }}
                                </a>
                            </li>
                        @endif
                    @endcan

                    @if(check_permission($sample->created_at, 'samples', $sample->user_id))
                        @can('samples-update')
                            <li><a  href="{{ route('samples.edit',$sample->id) }}"><i class="fal fa-pencil-alt"></i>{{('Edit')}}</a></li>
                        @endcan
                        @can('samples-delete')
                            <li>
                                <a href="{{ route('samples.destroy', $sample->id) }}" class="confirm-action" data-method="DELETE">
                                    <i class="fal fa-trash-alt"></i>
                                    {{ __('Delete') }}
                                </a>
                            </li>
                        @endcan
                    @elseif ($sample->user_id == auth()->id())
                        <li>
                            <a href="{{ route('notifications.send-request', ['id' => $sample->id, 'model' => 'Sample']) }}" class="confirm-action" data-method="GET">
                                <i class="fas fa-exclamation-triangle"></i>
                                {{ __('Permission Request') }}
                            </a>
                        </li>
                    @endif
                </ul>
                <ul class="dropdown-menu">
                    {{--                    <li><a href="{{ route('samples.show',$sample->id) }}" ><i class="fal fa-eye"></i>{{__('View')}}</a></li>--}}
                    <li><a target="_blank" href="{{ route('sample.print',$sample->id) }}"><i class="fas fa-print"></i>{{('Print')}}</a></li>
                    @can('samples-status')
                        @if($sample->status == 1)
                            <li>
                                <a href="javascript:void(0)" action="{{ route('sample.status', ['id' => $sample->id, 'status' => 2]) }}" class="change-status">
                                    <i class="far fa-check-circle"></i>
                                    {{__('Approved') }}
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" action="{{ route('sample.status', ['id' => $sample->id, 'status' => 0]) }}" class="change-status">
                                    <i class="far fa-times-circle"></i>
                                    {{__('Reject') }}
                                </a>
                            </li>
                        @endif
                    @endcan

                    @if(check_permission($sample->created_at, 'samples', $sample->user_id))
                        @can('samples-update')
                            <li><a  href="{{ route('samples.edit',$sample->id) }}"><i class="fal fa-pencil-alt"></i>{{('Edit')}}</a></li>
                        @endcan
                        @can('samples-delete')
                            <li>
                                <a href="{{ route('samples.destroy', $sample->id) }}" class="confirm-action" data-method="DELETE">
                                    <i class="fal fa-trash-alt"></i>
                                    {{ __('Delete') }}
                                </a>
                            </li>
                        @endcan
                    @elseif ($sample->user_id == auth()->id())
                        <li>
                            <a href="{{ route('notifications.send-request', ['id' => $sample->id, 'model' => 'Sample']) }}" class="confirm-action" data-method="GET">
                                <i class="fas fa-exclamation-triangle"></i>
                                {{ __('Permission Request') }}
                            </a>
                        </li>
                    @endif
                </ul>

            </div>
        </td>
    </tr>
@endforeach

@push('modal')
    @include('pages.components.approve-reject-modal')
@endpush
