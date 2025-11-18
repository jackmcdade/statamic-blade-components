<?php

use Octoper\BladeComponents\Tests\TestCase;

uses(TestCase::class);

test('simple hey component', function () {
    $component = renderAntler('{{ component:hey }}');

    expect($component)->toBeRenderedAs(
        <<<'EOF'
		<div>Hey</div>
		EOF
    );
});

test('simple hey component with name attribute', function () {
    $component = renderAntler('{{ component:hey name="Nick" }}');

    expect($component)->toBeRenderedAs(
        <<<'EOF'
		<div>Hey Nick</div>
		EOF
    );
});

test('section component with default slot', function () {
    $component = renderAntler(
        <<<'EOF'
			{{ component:section name="Main" }}
				Main Section
			{{ /component:section }}
		EOF
    );

    expect($component)->toBeRenderedAs(
        <<<'EOF'
			<div>
				<h1>Main</h1>
				<div>Main Section</div>
			</div>
		EOF
    );
});

test('section component with default slot and a Statamic variable', function () {
    $component = renderAntler(
        <<<'EOF'
			{{ component:section name="{name}" }}
				{{ name }} Section
			{{ /component:section }}
		EOF,
        [
            'name' => 'Main',
        ]
    );

    expect($component)->toBeRenderedAs(
        <<<'EOF'
			<div>
				<h1>Main</h1>
				<div>Main Section</div>
			</div>
		EOF
    );
});

test('card component with title slot', function () {
    $component = renderAntler(
        <<<'EOF'
			{{ component:card }}
				{{ component:slot name="title" }}
					Hey
				{{ /component:slot }}

				Hello
			{{ /component:card }}
		EOF
    );

    expect($component)->toBeRenderedAs(
        <<<'EOF'
			<div class="card">
				<h1>Hey</h1>
				<div>Hello</div>
			</div>
		EOF
    );
});

test('component alias x works', function () {
    $component = renderAntler('{{ x:hey name="World" }}');

    expect($component)->toBeRenderedAs(
        <<<'EOF'
		<div>Hey World</div>
		EOF
    );
});

test('component with multiple attributes', function () {
    $component = renderAntler('{{ component:hey name="John" class="text-lg" id="greeting" }}');

    expect($component)->toContain('Hey John');
    expect($component)->toContain('class="text-lg"');
    expect($component)->toContain('id="greeting"');
});

test('component with boolean attribute', function () {
    $component = renderAntler('{{ component:hey disabled="true" }}');

    expect($component)->toContain('disabled');
});

test('component with numeric attribute', function () {
    $component = renderAntler('{{ component:hey count="5" }}');

    expect($component)->toContain('count="5"');
});

test('component with empty content', function () {
    $component = renderAntler('{{ component:hey }}{{ /component:hey }}');

    expect($component)->toBeRenderedAs(
        <<<'EOF'
		<div>Hey</div>
		EOF
    );
});

test('component with nested components', function () {
    $component = renderAntler(
        <<<'EOF'
			{{ component:section name="Outer" }}
				{{ component:section name="Inner" }}
					Nested content
				{{ /component:section }}
			{{ /component:section }}
		EOF
    );

    expect($component)->toContain('Outer');
    expect($component)->toContain('Inner');
    expect($component)->toContain('Nested content');
});

test('slot without name returns empty', function () {
    $component = renderAntler('{{ component:slot }}{{ /component:slot }}');

    expect($component)->toBe('');
});

test('slot with empty name returns empty', function () {
    $component = renderAntler('{{ component:slot name="" }}{{ /component:slot }}');

    expect($component)->toBe('');
});

test('component with class attribute', function () {
    $component = renderAntler('{{ component:hey class="text-center font-bold" }}');

    expect($component)->toContain('class="text-center font-bold"');
});

test('component with data attributes', function () {
    $component = renderAntler('{{ component:hey data-id="123" data-name="test" }}');

    expect($component)->toContain('data-id="123"');
    expect($component)->toContain('data-name="test"');
});

test('component with special characters in attributes', function () {
    $component = renderAntler('{{ component:hey name="John & Jane" }}');

    expect($component)->toContain('John &amp; Jane');
});

test('component with Statamic variable in multiple attributes', function () {
    $component = renderAntler(
        '{{ component:hey name="{user_name}" class="{user_class}" }}',
        [
            'user_name' => 'Alice',
            'user_class' => 'highlight',
        ]
    );

    expect($component)->toContain('Hey Alice');
    expect($component)->toContain('class="highlight"');
});

test('component slot with attributes', function () {
    $component = renderAntler(
        <<<'EOF'
			{{ component:card }}
				{{ component:slot name="title" class="text-xl" }}
					Title
				{{ /component:slot }}
			{{ /component:card }}
		EOF
    );

    expect($component)->toContain('Title');
});

test('component with empty string attribute', function () {
    $component = renderAntler('{{ component:hey name="" }}');

    expect($component)->toBeRenderedAs(
        <<<'EOF'
		<div>Hey</div>
		EOF
    );
});
