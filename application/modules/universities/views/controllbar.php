<div class="dropdown">

    <button class="btn btn-link"
        type="button"
        title="Run Report"
        data-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded="false"
        <?= (($this->uri->rsegments[2] != 'detailed_info') ? 'disabled' : '') ?>>

        <i class="fa-regular fa-chart-bar"></i>

    </button>

    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
        <button title='Internal Report' class='btn-modalreport dropdown-item' data-reportfor="internal" <?= ((!user_group_check('GR_ADMIN', get_user()['id'])) ? 'disabled' : '') ?>>
            Internal Report
        </button>
        <div class="dropdown-divider"></div>
        <button title='University Report' class='btn-modalreport dropdown-item' data-reportfor="university">
            University Report
        </button>

    </div>

</div>