<?php

namespace Tests\Unit;

use App\Support\Files;
use Tests\TestCase;

class FilesUrlTest extends TestCase
{
    public function test_static_asset_and_external_urls(): void
    {
        $this->assertNull(Files::url(null));
        $this->assertStringEndsWith('/mivent_assets/poster-default.jpg', Files::url('mivent_assets/poster-default.jpg'));
        $this->assertSame('https://contoh.id/a.png', Files::url('https://contoh.id/a.png'));
    }
}
