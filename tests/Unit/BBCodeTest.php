<?php

declare(strict_types=1);

test('convert basic', function () {
    expect(convert_bbcode_basic('[b]bold[/b]'))->toBe('<strong>bold</strong>');
    expect(convert_bbcode_basic('[i]italic[/i]'))->toBe('<em>italic</em>');
    expect(convert_bbcode_basic('[u]underline[/u]'))->toBe('<u>underline</u>');
});

test('convert fonts', function () {
    expect(convert_bbcode_fonts('[size=7]test[/size]'))->toBe(' <font size=\'1\'> test </font> ');
    expect(convert_bbcode_fonts('[size=9]test[/size]'))->toBe(' <font size=\'2\'> test </font> ');
    expect(convert_bbcode_fonts('[size=12]test[/size]'))->toBe(' <font size=\'3\'> test </font> ');
    expect(convert_bbcode_fonts('[size=14]test[/size]'))->toBe(' <font size=\'4\'> test </font> ');
    expect(convert_bbcode_fonts('[size=18]test[/size]'))->toBe(' <font size=\'5\'> test </font> ');
    expect(convert_bbcode_fonts('[size=24]test[/size]'))->toBe(' <font size=\'6\'> test </font> ');
});
