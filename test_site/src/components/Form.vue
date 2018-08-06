<template>
  <div id="winnerForm">
    <div class="row">
      <div class="col-md-12 order-md-1">
        <h4 class="mb-3">賞品応募フォーム</h4>
        <form class="needs-validation" novalidate>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="firstName">姓</label>
              <input type="text" class="form-control" id="name_1" placeholder="" value="" required>
              <div class="invalid-feedback">
                Valid first name is required.
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <label for="lastName">名</label>
              <input type="text" class="form-control" id="name_2" placeholder="" value="" required>
              <div class="invalid-feedback">
                Valid last name is required.
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <label for="firstName">セイ</label>
              <input type="text" class="form-control" id="kana_1" placeholder="" value="" required>
              <div class="invalid-feedback">
                Valid first name is required.
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <label for="lastName">メイ</label>
              <input type="text" class="form-control" id="kana_2" placeholder="" value="" required>
              <div class="invalid-feedback">
                Valid last name is required.
              </div>
            </div>
          </div>

          <hr />
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="email">Email</label>
              <input type="email" class="form-control" id="email" placeholder="you@example.com">
              <div class="invalid-feedback">
                Please enter a valid email address for shipping updates.
              </div>
            </div>
          </div>

          <hr />
          <div class="row">
            <div class="col-md-12 ">
            <label for="zip">郵便番号</label>
            </div>
            <div class="col-md-2 mb-3">
              <input type="text" class="form-control" maxlength="3" name="zip_1" placeholder="" required>
              <div class="invalid-feedback">
                Zip code required.
              </div>
            </div>
            <div class="mt-1">
              -
            </div>
            <div class="col-md-3 mb-3 ">
              <input type="text" class="form-control" maxlength="4" name="zip_2" placeholder="" required>
              <div class="invalid-feedback">
                Zip code required.
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-5 mb-3">
              <label for="country">都道府県</label>
              <select class="custom-select d-block w-100" id="country" required>
                <option value="">選択してください</option>
                <option>United States</option>
              </select>
              <div class="invalid-feedback">
                Please select a valid country.
              </div>
            </div>
          </div>

          <div class=" mb-3">
            <label for="address">住所 1</label>
            <input type="text" class="form-control" id="address_1" placeholder="1234 Main St" required>
            <div class="invalid-feedback">
              Please enter your shipping address.
            </div>
          </div>
          <div class="mb-5">
            <label for="address2">住所 2 <span class="text-muted">(任意)</span></label>
            <input type="text" class="form-control" id="address_2" placeholder="Apartment or suite">
          </div>

          <button class="btn btn-primary btn-lg " type="submit">確認</button>
        </form>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'winnerForm'
}
</script>
