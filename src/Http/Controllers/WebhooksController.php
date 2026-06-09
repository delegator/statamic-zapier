<?php

namespace Delegator\ZapierForms\Http\Controllers;

use Statamic\Facades\User;
use Illuminate\Http\Request;
use Statamic\CP\PublishForm;
use Delegator\ZapierForms\Webhooks;
use Statamic\Http\Controllers\CP\CpController;
use Statamic\Support\Arr;
use Inertia\Inertia;

class WebhooksController extends CpController
{
    public function edit()
    {
        abort_unless(User::current()->can('configure form zapier webhooks'), 403);

        $blueprint = Webhooks::blueprint();

        $fields = $blueprint
            ->fields()
            ->addValues(Webhooks::load()->all())
            ->preProcess();

        return PublishForm::make($blueprint)
          ->title("Zapier Forms")
          ->icon('hierarchy-hub-integration-connection')
          ->values($fields->values()->all())
          ->submittingTo(cp_route('zapier-forms.update'), 'POST');
    }

    public function update(Request $request)
    {
        abort_unless(User::current()->can('configure form zapier webhooks'), 403);

        $blueprint = Webhooks::blueprint();

        $fields = $blueprint->fields()->addValues($request->all());

        $fields->validate();

        $values = Arr::removeNullValues($fields->process()->values()->all());

        Webhooks::load($values)->save();
    }
}
