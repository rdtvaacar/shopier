<div class="form-group">
    <label>İlçe</label>
    <select required name="county" class="form-control">
        @foreach ($counties as $county)
            <?php  $select = $county->id == @$adress->county_id ? 'selected="selected"' : ''  ?>
            <option {{ $select }} value="{{ $county->id }}">
                {{$county->name}}
            </option>
        @endforeach
    </select>
</div>