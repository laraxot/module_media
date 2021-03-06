<?php

declare(strict_types=1);

namespace Modules\Media\Models\Panels\Policies;

use Modules\Xot\Contracts\PanelContract;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Models\Panels\Policies\XotBasePanelPolicy;

class _ModulePanelPolicy extends XotBasePanelPolicy {
    public function test(UserContract $user, PanelContract $panel): bool {
        return true;
    }

    public function choosePubTheme(UserContract $user, PanelContract $panel): bool {
        return true;
    }

    public function activatePubTheme(UserContract $user, PanelContract $panel): bool {
        return true;
    }

    public function chooseAdmTheme(UserContract $user, PanelContract $panel): bool {
        return true;
    }

    public function activateAdmTheme(UserContract $user, PanelContract $panel): bool {
        return true;
    }

    public function chooseIcons(UserContract $user, PanelContract $panel): bool {
        return true;
    }

    public function showAllIcons(UserContract $user, PanelContract $panel): bool {
        return true;
    }

    public function manageLangModule(UserContract $user, PanelContract $panel): bool {
        return true;
    }

    public function testVideo(UserContract $user, PanelContract $panel): bool {
        return true;
    }

    public function testVideoPlayer(UserContract $user, PanelContract $panel): bool {
        return true;
    }

    public function testVideoEditor(UserContract $user, PanelContract $panel): bool {
        return true;
    }

    public function testContentSelectionAndHighlighting(UserContract $user, PanelContract $panel): bool {
        return true;
    }

    public function TestSelectHighlight(UserContract $user, PanelContract $panel): bool {
        return true;
    }

    public function testSlider(UserContract $user, PanelContract $panel): bool {
        return true;
    }

    public function populateVideo(UserContract $user, PanelContract $panel): bool {
        return true;
    }

    public function testStreaming(UserContract $user, PanelContract $panel): bool {
        return true;
    }

    public function fillVideoFromDirectory(UserContract $user, PanelContract $panel): bool {
        return true;
    }

    public function tryVideoEditorSub(UserContract $user, PanelContract $panel): bool {
        return true;
    }

    public function tryStream(UserContract $user, PanelContract $panel): bool {
        return true;
    }
}
