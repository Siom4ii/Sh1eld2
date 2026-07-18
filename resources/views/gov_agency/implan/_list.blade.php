<div class="table-responsive">
    <table class="table table-hover">
        <tbody>
            @forelse ($items as $im)
                <tr>
                    <td>
                        <a href="{{ route('gov_agency.implan.show', $im) }}" class="font-weight-medium text-primary">
                            {{ Str::limit($im->issues, 80) ?: 'IMPLAN #'.$im->id }}
                        </a>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('gov_agency.implan.show', $im) }}" class="btn btn-sm btn-outline-secondary">Manage</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="2" class="text-center text-muted py-4">{{ $empty }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
